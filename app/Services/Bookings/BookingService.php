<?php

namespace App\Services\Bookings;

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Enum\PaymentStatus;
use App\Mail\BookingPaymentSucceeded;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class BookingService
{
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
     */
    public function createFawryBooking(Package $package, array $data, string $locale): Booking
    {
        return DB::transaction(function () use ($package, $data, $locale): Booking {
            $booking = Booking::create([
                ...$this->bookingAttributes($package, $data, $locale),
                'type' => PackageOrderAction::FawryPayment,
                'status' => BookingStatus::AwaitingPayment,
            ]);

            $booking->payment()->create([
                'provider' => 'fawry',
                'merchant_ref_number' => $this->merchantReference($booking),
                'payment_method' => 'FawryPayCheckoutButton',
                'status' => PaymentStatus::Pending,
                'amount' => $booking->total_amount,
                'currency' => $booking->currency,
            ]);

            return $booking->loadMissing('package', 'payment');
        });
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function applyFawryStatus(array $payload, string $payloadColumn = 'webhook_payload'): ?Payment
    {
        $merchantReference = (string) (Arr::get($payload, 'merchantRefNumber') ?: Arr::get($payload, 'merchantRefNum'));

        if ($merchantReference === '') {
            return null;
        }

        $payment = Payment::query()
            ->where('merchant_ref_number', $merchantReference)
            ->with('booking.package')
            ->first();

        if (! $payment) {
            return null;
        }

        $paymentAmount = Arr::get($payload, 'paymentAmount', Arr::get($payload, 'orderAmount'));

        if ($paymentAmount !== null && abs((float) $paymentAmount - (float) $payment->amount) > 0.01) {
            throw ValidationException::withMessages([
                'amount' => 'Fawry amount does not match the booking amount.',
            ]);
        }

        $status = $this->paymentStatusFromFawry($this->fawryStatusFromPayload($payload));
        $paidAt = $status === PaymentStatus::Paid ? now() : $payment->paid_at;
        $payloadColumn = in_array($payloadColumn, ['response_payload', 'webhook_payload'], true)
            ? $payloadColumn
            : 'webhook_payload';

        DB::transaction(function () use ($payment, $payload, $status, $paidAt, $payloadColumn): void {
            $payment->update([
                'fawry_reference_number' => Arr::get(
                    $payload,
                    'referenceNumber',
                    Arr::get(
                        $payload,
                        'fawryRefNumber',
                        Arr::get($payload, 'paymentRefrenceNumber', $payment->fawry_reference_number)
                    )
                ),
                'status' => $status,
                'fawry_fees' => Arr::get($payload, 'fawryFees', $payment->fawry_fees),
                'paid_at' => $paidAt,
                $payloadColumn => $payload,
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

    public function syncBookingFromPayment(Payment $payment): void
    {
        $payment->loadMissing('booking');

        $status = $this->bookingStatusFromPayment($payment->status);
        $paidAt = $payment->status === PaymentStatus::Paid
            ? ($payment->paid_at ?? now())
            : $payment->booking->paid_at;

        DB::transaction(function () use ($payment, $status, $paidAt): void {
            if ($payment->status === PaymentStatus::Paid && $payment->paid_at === null) {
                $payment->update(['paid_at' => $paidAt]);
            }

            $payment->booking->update([
                'status' => $status,
                'paid_at' => $paidAt,
            ]);
        });

        if ($payment->status === PaymentStatus::Paid) {
            $this->sendPaymentSuccessEmail($payment->booking);
        }
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
            'currency' => 'EGP',
        ];
    }

    protected function merchantReference(Booking $booking): string
    {
        return 'BK-'.$booking->id.'-'.now()->format('YmdHis');
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    protected function fawryStatusFromPayload(array $payload): string
    {
        return (string) (
            Arr::get($payload, 'orderStatus')
            ?: Arr::get($payload, 'paymentStatus')
            ?: Arr::get($payload, 'payment_status')
            ?: 'PENDING'
        );
    }

    protected function paymentStatusFromFawry(string $status): PaymentStatus
    {
        return match (strtoupper($status)) {
            'PAID' => PaymentStatus::Paid,
            'EXPIRED' => PaymentStatus::Expired,
            'CANCELED', 'CANCELLED' => PaymentStatus::Failed,
            'REFUNDED' => PaymentStatus::Refunded,
            'PARTIAL_REFUNDED' => PaymentStatus::PartialRefunded,
            'FAILED', 'UNPAID', 'REJECTED' => PaymentStatus::Failed,
            'NEW', 'CREATED', 'PROCESSING' => PaymentStatus::Pending,
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
            PaymentStatus::Refunded => BookingStatus::Refunded,
            PaymentStatus::PartialRefunded => BookingStatus::PartialRefunded,
            PaymentStatus::Pending => BookingStatus::AwaitingPayment,
        };
    }
}
