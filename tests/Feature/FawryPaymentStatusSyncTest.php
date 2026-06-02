<?php

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Enum\PaymentStatus;
use App\Mail\BookingPaymentSucceeded;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'fawry.merchant_code' => 'TEST-MERCHANT',
        'fawry.secure_key' => 'secret',
        'fawry.status_endpoint' => 'https://example.test/fawry/status/v2',
    ]);
});

test('paid inquiry updates payment to paid', function () {
    Mail::fake();
    [$booking, $payment] = pendingFawryPayment();

    Http::fake([
        'https://example.test/fawry/status/v2*' => Http::response([
            'type' => 'PaymentStatusResponse',
            'fawryRefNumber' => '1122334455',
            'merchantRefNumber' => $payment->merchant_ref_number,
            'paymentAmount' => '3000.00',
            'orderAmount' => '3000.00',
            'orderStatus' => 'PAID',
            'paymentMethod' => 'CARD',
            'paymentRefrenceNumber' => '99887766',
        ]),
    ]);

    $this->artisan('fawry:sync-payments')->assertSuccessful();

    expect($payment->refresh())
        ->status->toBe(PaymentStatus::Paid)
        ->fawry_reference_number->toBe('1122334455')
        ->paid_at->not->toBeNull()
        ->response_payload->orderStatus->toBe('PAID')
        ->and($booking->refresh())
        ->status->toBe(BookingStatus::Paid)
        ->paid_at->not->toBeNull()
        ->payment_success_email_sent_at->not->toBeNull();

    Mail::assertQueued(BookingPaymentSucceeded::class);

    Http::assertSent(fn ($request): bool => $request->url() === 'https://example.test/fawry/status/v2?merchantCode=TEST-MERCHANT&merchantRefNumber='.$payment->merchant_ref_number.'&signature='.hash('sha256', 'TEST-MERCHANT'.$payment->merchant_ref_number.'secret'));
});

test('failed inquiry updates payment to failed', function () {
    [$booking, $payment] = pendingFawryPayment();

    Http::fake([
        'https://example.test/fawry/status/v2*' => Http::response([
            'type' => 'PaymentStatusResponse',
            'fawryRefNumber' => '1122334455',
            'merchantRefNumber' => $payment->merchant_ref_number,
            'paymentAmount' => '3000.00',
            'orderAmount' => '3000.00',
            'orderStatus' => 'FAILED',
            'failureReason' => 'Rejected test card',
        ]),
    ]);

    $this->artisan('fawry:sync-payments')->assertSuccessful();

    expect($payment->refresh())
        ->status->toBe(PaymentStatus::Failed)
        ->paid_at->toBeNull()
        ->response_payload->orderStatus->toBe('FAILED')
        ->and($booking->refresh())
        ->status->toBe(BookingStatus::Failed)
        ->paid_at->toBeNull();
});

test('unknown inquiry status keeps payment pending', function () {
    [$booking, $payment] = pendingFawryPayment();

    Http::fake([
        'https://example.test/fawry/status/v2*' => Http::response([
            'type' => 'PaymentStatusResponse',
            'merchantRefNumber' => $payment->merchant_ref_number,
            'paymentAmount' => '3000.00',
            'orderAmount' => '3000.00',
            'orderStatus' => 'NEW',
        ]),
    ]);

    $this->artisan('fawry:sync-payments')->assertSuccessful();

    expect($payment->refresh())
        ->status->toBe(PaymentStatus::Pending)
        ->paid_at->toBeNull()
        ->response_payload->orderStatus->toBe('NEW')
        ->and($booking->refresh())
        ->status->toBe(BookingStatus::AwaitingPayment)
        ->paid_at->toBeNull();
});

test('payments older than max-age are skipped', function () {
    Http::fake();

    [$booking, $payment] = pendingFawryPayment();

    DB::table('payments')
        ->where('id', $payment->id)
        ->update(['created_at' => now()->subHours(72)]);

    $this->artisan('fawry:sync-payments --max-age=48')->assertSuccessful();

    Http::assertNothingSent();
    expect($payment->refresh()->status)->toBe(PaymentStatus::Pending);
});

test('sync stops after reaching the limit', function () {
    Http::fake([
        'https://example.test/fawry/status/v2*' => Http::response([
            'type' => 'PaymentStatusResponse',
            'orderStatus' => 'NEW',
            'paymentAmount' => '3000.00',
            'orderAmount' => '3000.00',
        ]),
    ]);

    $payments = collect(range(1, 5))->map(function () {
        [$booking, $payment] = pendingFawryPayment();

        return $payment;
    });

    $this->artisan('fawry:sync-payments --limit=2')->assertSuccessful();

    Http::assertSentCount(2);
});

/**
 * @return array{0: Booking, 1: Payment}
 */
function pendingFawryPayment(): array
{
    $booking = Booking::factory()->create([
        'status' => BookingStatus::AwaitingPayment,
        'type' => PackageOrderAction::FawryPayment,
        'total_amount' => 3000,
        'currency' => 'EGP',
    ]);
    $payment = Payment::factory()->create([
        'booking_id' => $booking->id,
        'merchant_ref_number' => 'BK-'.$booking->id.'-20260520120000',
        'status' => PaymentStatus::Pending,
        'amount' => 3000,
        'currency' => 'EGP',
        'expires_at' => now()->addHour(),
    ]);

    return [$booking, $payment];
}
