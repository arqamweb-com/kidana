<?php

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Enum\PaymentStatus;
use App\Mail\BookingPaymentSucceeded;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\Bookings\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

test('syncing a paid payment marks the booking as paid and sets paid_at', function () {
    Mail::fake();

    [$booking, $payment] = awaitingPayment();

    $payment->update(['status' => PaymentStatus::Paid]);

    app(BookingService::class)->syncBookingFromPayment($payment);

    expect($booking->refresh())
        ->status->toBe(BookingStatus::Paid)
        ->paid_at->not->toBeNull();

    expect($payment->refresh())
        ->paid_at->not->toBeNull();
});

test('syncing a paid payment sends the payment success email once', function () {
    Mail::fake();

    [$booking, $payment] = awaitingPayment();

    $payment->update(['status' => PaymentStatus::Paid]);

    app(BookingService::class)->syncBookingFromPayment($payment);

    Mail::assertQueued(BookingPaymentSucceeded::class);

    app(BookingService::class)->syncBookingFromPayment($payment->refresh());

    Mail::assertQueuedCount(1);
});

test('syncing a failed payment marks the booking as failed', function () {
    Mail::fake();

    [$booking, $payment] = awaitingPayment();

    $payment->update(['status' => PaymentStatus::Failed]);

    app(BookingService::class)->syncBookingFromPayment($payment);

    expect($booking->refresh())
        ->status->toBe(BookingStatus::Failed)
        ->paid_at->toBeNull();

    Mail::assertNothingQueued();
});

test('syncing an expired payment marks the booking as expired', function () {
    Mail::fake();

    [$booking, $payment] = awaitingPayment();

    $payment->update(['status' => PaymentStatus::Expired]);

    app(BookingService::class)->syncBookingFromPayment($payment);

    expect($booking->refresh())
        ->status->toBe(BookingStatus::Expired)
        ->paid_at->toBeNull();

    Mail::assertNothingQueued();
});

test('syncing a pending payment keeps booking awaiting payment', function () {
    Mail::fake();

    [$booking, $payment] = awaitingPayment();

    app(BookingService::class)->syncBookingFromPayment($payment);

    expect($booking->refresh())
        ->status->toBe(BookingStatus::AwaitingPayment)
        ->paid_at->toBeNull();

    Mail::assertNothingQueued();
});

/**
 * @return array{0: Booking, 1: Payment}
 */
function awaitingPayment(): array
{
    $booking = Booking::factory()->create([
        'status' => BookingStatus::AwaitingPayment,
        'type' => PackageOrderAction::FawryPayment,
        'total_amount' => 3000,
        'currency' => 'EGP',
    ]);

    $payment = Payment::factory()->create([
        'booking_id' => $booking->id,
        'status' => PaymentStatus::Pending,
        'amount' => 3000,
        'currency' => 'EGP',
    ]);

    return [$booking, $payment];
}
