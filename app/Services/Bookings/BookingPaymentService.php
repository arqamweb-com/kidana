<?php

namespace App\Services\Bookings;

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Enum\PaymentStatus;
use App\Mail\BookingPaymentSucceeded;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use App\Services\Fawry\FawryClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class BookingPaymentService
{
    public function __construct(private readonly FawryClient $fawry) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function createCustomBooking(Package $package, array $data, string $locale): Booking
    {
        return Booking::create([
            ...$this->bookingAttributes($package, $data, $locale),
            'type' => PackageOrderAction::CustomForm,
            'status' => BookingStatus::Pending,
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{booking: Booking, payment: Payment, response: array<string, mixed>}
     */
    public function createFawryPayment(Package $package, array $data, string $locale): array
    {
        if (! $this->fawry->isConfigured()) {
            throw ValidationException::withMessages([
                'payment' => __('packages.booking.payment_not_configured'),
            ]);
        }

        [$booking, $payment] = DB::transaction(function () use ($package, $data, $locale): array {
            $booking = Booking::create([
                ...$this->bookingAttributes($package, $data, $locale),
                'type' => PackageOrderAction::FawryPayment,
                'status' => BookingStatus::AwaitingPayment,
            ]);

            $payment = $booking->payment()->create([
                'provider' => 'fawry',
                'merchant_ref_number' => $this->merchantReference($booking),
                'payment_method' => (string) config('fawry.payment_method'),
                'status' => PaymentStatus::Pending,
                'amount' => $booking->total_amount,
                'currency' => $booking->currency,
                'expires_at' => now()->addMinutes((int) config('fawry.expiry_minutes')),
            ]);

            $payment->update([
                'request_payload' => $this->fawry->buildHostedCheckoutPayload($booking->loadMissing('package'), $payment),
            ]);

            return [$booking, $payment];
        });

        $response = $this->fawry->createHostedCheckout($booking->loadMissing('package'), $payment);

        $payment->update([
            'fawry_reference_number' => Arr::get($response, 'referenceNumber') ?: Arr::get($response, 'fawryRefNumber'),
            'status' => $this->paymentStatusFromFawry((string) Arr::get($response, 'orderStatus', 'PENDING')),
            'fawry_fees' => Arr::get($response, 'fawryFees'),
            'response_payload' => $response,
        ]);

        return [
            'booking' => $booking->refresh(),
            'payment' => $payment->refresh(),
            'response' => $response,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function applyFawryNotification(array $payload): Payment
    {
        if (! $this->fawry->notificationSignatureMatches($payload)) {
            throw ValidationException::withMessages([
                'signature' => 'Invalid Fawry notification signature.',
            ]);
        }

        $merchantReference = (string) (Arr::get($payload, 'merchantRefNum') ?: Arr::get($payload, 'merchantRefNumber'));
        $payment = Payment::query()
            ->where('merchant_ref_number', $merchantReference)
            ->with('booking.package')
            ->firstOrFail();

        $paymentAmount = (float) Arr::get($payload, 'paymentAmount', Arr::get($payload, 'amount', $payment->amount));

        if (abs($paymentAmount - (float) $payment->amount) > 0.01) {
            throw ValidationException::withMessages([
                'amount' => 'Fawry notification amount does not match the payment amount.',
            ]);
        }

        $status = $this->paymentStatusFromFawry((string) Arr::get($payload, 'orderStatus', 'PENDING'));

        DB::transaction(function () use ($payment, $payload, $status): void {
            $paidAt = $status === PaymentStatus::Paid
                ? now()
                : $payment->paid_at;

            $payment->update([
                'fawry_reference_number' => Arr::get($payload, 'fawryRefNumber', $payment->fawry_reference_number),
                'status' => $status,
                'fawry_fees' => Arr::get($payload, 'fawryFees', $payment->fawry_fees),
                'paid_at' => $paidAt,
                'webhook_payload' => $payload,
            ]);

            $payment->booking->update([
                'status' => $this->bookingStatusFromPayment($status),
                'paid_at' => $paidAt,
            ]);
        });

        $payment->refresh()->loadMissing('booking.package');

        if ($payment->status === PaymentStatus::Paid) {
            $this->sendPaymentSuccessEmail($payment->booking);
        }

        return $payment;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function applyFawryReturnResponse(array $payload): ?Payment
    {
        if (! $this->fawry->chargeResponseSignatureMatches($payload)) {
            return null;
        }

        $merchantReference = (string) (Arr::get($payload, 'merchantRefNumber') ?: Arr::get($payload, 'merchantRefNum'));
        $payment = Payment::query()
            ->where('merchant_ref_number', $merchantReference)
            ->with('booking.package')
            ->first();

        if (! $payment) {
            return null;
        }

        $status = $this->paymentStatusFromFawry((string) Arr::get($payload, 'orderStatus', 'PENDING'));
        $paidAt = $status === PaymentStatus::Paid ? now() : $payment->paid_at;

        DB::transaction(function () use ($payment, $payload, $status, $paidAt): void {
            $payment->update([
                'fawry_reference_number' => Arr::get($payload, 'referenceNumber', $payment->fawry_reference_number),
                'status' => $status,
                'fawry_fees' => Arr::get($payload, 'fawryFees', $payment->fawry_fees),
                'paid_at' => $paidAt,
                'webhook_payload' => $payload,
            ]);

            $payment->booking->update([
                'status' => $this->bookingStatusFromPayment($status),
                'paid_at' => $paidAt,
            ]);
        });

        $payment->refresh()->loadMissing('booking.package');

        if ($payment->status === PaymentStatus::Paid) {
            $this->sendPaymentSuccessEmail($payment->booking);
        }

        return $payment;
    }

    protected function sendPaymentSuccessEmail(Booking $booking): void
    {
        if ($booking->payment_success_email_sent_at !== null) {
            return;
        }

        Mail::to($booking->customer_email)->queue(new BookingPaymentSucceeded($booking));

        $booking->forceFill([
            'payment_success_email_sent_at' => now(),
        ])->save();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function bookingAttributes(Package $package, array $data, string $locale): array
    {
        return [
            'package_id' => $package->id,
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_mobile' => $data['customer_mobile'],
            'guests' => $data['guests'] ?? null,
            'travel_date' => $data['travel_date'] ?? null,
            'message' => $data['message'] ?? null,
            'locale' => $locale,
            'total_amount' => $package->price,
            'currency' => (string) config('fawry.currency'),
        ];
    }

    protected function merchantReference(Booking $booking): string
    {
        return 'BK-'.$booking->id.'-'.now()->format('YmdHis');
    }

    protected function paymentStatusFromFawry(string $status): PaymentStatus
    {
        return match (strtoupper($status)) {
            'PAID' => PaymentStatus::Paid,
            'EXPIRED' => PaymentStatus::Expired,
            'CANCELED', 'CANCELLED' => PaymentStatus::Cancelled,
            'FAILED', 'UNPAID' => PaymentStatus::Failed,
            default => PaymentStatus::Pending,
        };
    }

    protected function bookingStatusFromPayment(PaymentStatus $status): BookingStatus
    {
        return match ($status) {
            PaymentStatus::Paid => BookingStatus::Paid,
            PaymentStatus::Failed => BookingStatus::Failed,
            PaymentStatus::Expired => BookingStatus::Expired,
            PaymentStatus::Cancelled => BookingStatus::Cancelled,
            PaymentStatus::Pending => BookingStatus::AwaitingPayment,
        };
    }
}
