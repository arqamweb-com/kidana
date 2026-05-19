<?php

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Enum\PaymentStatus;
use App\Mail\BookingPaymentSucceeded;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('package order button follows the dashboard order action', function () {
    $customPackage = Package::factory()->create([
        'slug' => 'custom-request',
        'order_action' => PackageOrderAction::CustomForm,
    ]);
    $paymentPackage = Package::factory()->create([
        'slug' => 'fawry-request',
        'order_action' => PackageOrderAction::FawryPayment,
    ]);

    $this->get(route('packages.show', ['package' => $customPackage->slug]))
        ->assertSuccessful()
        ->assertSee(route('packages.request', ['package' => $customPackage->slug]), false)
        ->assertSee(__('packages.show.book_now'));

    $this->get(route('packages.show', ['package' => $paymentPackage->slug]))
        ->assertSuccessful()
        ->assertSee(route('packages.checkout', ['package' => $paymentPackage->slug]), false)
        ->assertSee(__('packages.show.pay_now'));
});

test('custom package request stores a booking', function () {
    $package = Package::factory()->create([
        'price' => 2500,
        'order_action' => PackageOrderAction::CustomForm,
    ]);

    $response = $this->post(route('packages.request.store', ['package' => $package->slug]), bookingPayload());

    $response->assertSuccessful();
    $response->assertSee('Your request has been received');

    $booking = Booking::first();

    expect($booking)
        ->not->toBeNull()
        ->status->toBe(BookingStatus::Pending)
        ->type->toBe(PackageOrderAction::CustomForm)
        ->total_amount->toBe('2500.00');
});

test('fawry checkout redirects to hosted card checkout', function () {
    config([
        'fawry.merchant_code' => 'TEST-MERCHANT',
        'fawry.secure_key' => 'secret',
        'fawry.endpoint' => 'https://example.test/fawry',
        'fawry.payment_method' => 'CARD',
    ]);

    Http::fake([
        'https://example.test/fawry' => Http::response([
            'nextAction' => [
                'type' => 'THREE_D_SECURE',
                'redirectUrl' => 'https://atfawry.fawrystaging.com/checkout/abc123',
            ],
        ]),
    ]);

    $package = Package::factory()->create([
        'price' => 3000,
        'order_action' => PackageOrderAction::FawryPayment,
    ]);

    $response = $this->post(route('packages.checkout.store', ['package' => $package->slug]), bookingPayload());

    $response->assertRedirect('https://atfawry.fawrystaging.com/checkout/abc123');

    $payment = Payment::first();

    expect($payment)
        ->not->toBeNull()
        ->status->toBe(PaymentStatus::Pending)
        ->amount->toBe('3000.00')
        ->fawry_reference_number->toBeNull()
        ->payment_method->toBe('CARD');

    Http::assertSent(fn ($request): bool => $request['merchantCode'] === 'TEST-MERCHANT'
        && $request['merchantRefNum'] === $payment->merchant_ref_number
        && $request['amount'] === '3000.00'
        && $request['paymentMethod'] === 'CARD'
        && filled($request['returnUrl'])
        && filled($request['orderWebHookUrl']));
});

test('fawry checkout handles a json string hosted checkout url', function () {
    config([
        'fawry.merchant_code' => 'TEST-MERCHANT',
        'fawry.secure_key' => 'secret',
        'fawry.endpoint' => 'https://example.test/fawry',
        'fawry.payment_method' => 'CARD',
    ]);

    Http::fake([
        'https://example.test/fawry' => Http::response('"https://atfawry.fawrystaging.com/checkout/json-string"', 200, [
            'Content-Type' => 'application/json',
        ]),
    ]);

    $package = Package::factory()->create([
        'price' => 3000,
        'order_action' => PackageOrderAction::FawryPayment,
    ]);

    $this
        ->post(route('packages.checkout.store', ['package' => $package->slug]), bookingPayload())
        ->assertRedirect('https://atfawry.fawrystaging.com/checkout/json-string');
});

test('fawry checkout does not show a reference page when checkout url is missing', function () {
    config([
        'fawry.merchant_code' => 'TEST-MERCHANT',
        'fawry.secure_key' => 'secret',
        'fawry.endpoint' => 'https://example.test/fawry',
        'fawry.payment_method' => 'CARD',
    ]);

    Http::fake([
        'https://example.test/fawry' => Http::response([
            'referenceNumber' => '987654321',
            'orderStatus' => 'PENDING',
        ]),
    ]);

    $package = Package::factory()->create([
        'price' => 3000,
        'order_action' => PackageOrderAction::FawryPayment,
    ]);

    $this
        ->from(route('packages.checkout', ['package' => $package->slug]))
        ->post(route('packages.checkout.store', ['package' => $package->slug]), bookingPayload())
        ->assertRedirect(route('packages.checkout', ['package' => $package->slug]))
        ->assertSessionHasErrors('payment');
});

test('fawry webhook marks booking paid and queues confirmation email', function () {
    config([
        'fawry.secure_key' => 'secret',
    ]);
    Mail::fake();

    $booking = Booking::factory()->create([
        'status' => BookingStatus::AwaitingPayment,
        'total_amount' => 3000,
        'currency' => 'EGP',
    ]);
    $payment = Payment::factory()->create([
        'booking_id' => $booking->id,
        'merchant_ref_number' => 'BK-1-20260519190000',
        'status' => PaymentStatus::Pending,
        'amount' => 3000,
        'currency' => 'EGP',
    ]);
    $payload = [
        'fawryRefNumber' => '1122334455',
        'merchantRefNum' => $payment->merchant_ref_number,
        'paymentAmount' => '3000.00',
        'orderAmount' => '3000.00',
        'orderStatus' => 'PAID',
        'paymentMethod' => 'CARD',
        'paymentReferenceNumber' => '1122334455',
    ];
    $payload['messageSignature'] = fawryNotificationSignature($payload, 'secret');

    $this->postJson(route('payments.fawry.webhook'), $payload)
        ->assertSuccessful()
        ->assertJson(['received' => true]);

    expect($payment->refresh())
        ->status->toBe(PaymentStatus::Paid)
        ->fawry_reference_number->toBe('1122334455')
        ->and($booking->refresh())
        ->status->toBe(BookingStatus::Paid)
        ->paid_at->not->toBeNull()
        ->payment_success_email_sent_at->not->toBeNull();

    Mail::assertQueued(BookingPaymentSucceeded::class);
});

test('fawry return response can mark booking paid', function () {
    config([
        'fawry.secure_key' => 'secret',
    ]);
    Mail::fake();

    $booking = Booking::factory()->create([
        'status' => BookingStatus::AwaitingPayment,
        'total_amount' => 3000,
        'currency' => 'EGP',
    ]);
    $payment = Payment::factory()->create([
        'booking_id' => $booking->id,
        'merchant_ref_number' => 'BK-2-20260520100000',
        'status' => PaymentStatus::Pending,
        'amount' => 3000,
        'currency' => 'EGP',
        'payment_method' => 'CARD',
    ]);
    $payload = [
        'referenceNumber' => '99887766',
        'merchantRefNumber' => $payment->merchant_ref_number,
        'paymentAmount' => '3000.00',
        'orderAmount' => '3000.00',
        'orderStatus' => 'PAID',
        'paymentMethod' => 'CARD',
        'fawryFees' => '0.00',
        'customerMail' => $booking->customer_email,
        'customerMobile' => $booking->customer_mobile,
    ];
    $payload['signature'] = fawryReturnSignature($payload, 'secret');

    $this->get(route('bookings.result', ['booking' => $booking->id, ...$payload]))
        ->assertSuccessful()
        ->assertSee('Payment successful')
        ->assertSee('99887766');

    expect($payment->refresh())
        ->status->toBe(PaymentStatus::Paid)
        ->fawry_reference_number->toBe('99887766')
        ->and($booking->refresh())
        ->status->toBe(BookingStatus::Paid)
        ->paid_at->not->toBeNull();

    Mail::assertQueued(BookingPaymentSucceeded::class);
});

/**
 * @return array<string, mixed>
 */
function bookingPayload(): array
{
    return [
        'customer_name' => 'Ahmed Mohamed',
        'customer_email' => 'ahmed@example.com',
        'customer_mobile' => '01012345678',
        'guests' => 2,
        'travel_date' => now()->addMonth()->toDateString(),
        'message' => 'Please confirm availability.',
    ];
}

/**
 * @param  array<string, mixed>  $payload
 */
function fawryNotificationSignature(array $payload, string $secureKey): string
{
    return hash('sha256', implode('', [
        $payload['fawryRefNumber'],
        $payload['merchantRefNum'],
        $payload['paymentAmount'],
        $payload['orderAmount'],
        $payload['orderStatus'],
        $payload['paymentMethod'],
        $payload['paymentReferenceNumber'],
        $secureKey,
    ]));
}

/**
 * @param  array<string, mixed>  $payload
 */
function fawryReturnSignature(array $payload, string $secureKey): string
{
    return hash('sha256', implode('', [
        $payload['referenceNumber'],
        $payload['merchantRefNumber'],
        $payload['paymentAmount'],
        $payload['orderAmount'],
        $payload['orderStatus'],
        $payload['paymentMethod'],
        $payload['fawryFees'],
        '',
        '',
        $payload['customerMail'],
        $payload['customerMobile'],
        $secureKey,
    ]));
}
