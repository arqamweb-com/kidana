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

test('fawry checkout creates a booking and shows the checkout button page', function () {
    config([
        'fawry.merchant_code' => 'TEST-MERCHANT',
        'fawry.secure_key' => 'secret',
        'fawry.mode' => 'sandbox',
    ]);

    $package = Package::factory()->create([
        'price' => 3000,
        'order_action' => PackageOrderAction::FawryPayment,
    ]);

    $response = $this->post(route('packages.checkout.store', ['package' => $package->slug]), bookingPayload());

    $booking = Booking::query()->with('payment')->first();
    $payment = $booking->payment;

    $response->assertRedirect(route('payment.checkout', [
        'booking' => $booking,
    ]));

    expect($booking)
        ->status->toBe(BookingStatus::AwaitingPayment)
        ->type->toBe(PackageOrderAction::FawryPayment)
        ->total_amount->toBe('3000.00')
        ->and($payment)
        ->not->toBeNull()
        ->status->toBe(PaymentStatus::Pending)
        ->amount->toBe('3000.00')
        ->payment_method->toBe('FawryPayCheckoutButton');

    $this->get(route('payment.checkout', ['booking' => $booking]))
        ->assertSuccessful()
        ->assertSee('fawrypay-payments.js', false)
        ->assertSee('FawryPay.checkout', false)
        ->assertSee($payment->merchant_ref_number);

    $payload = $payment->refresh()->request_payload;

    expect($payload)
        ->merchantCode->toBe('TEST-MERCHANT')
        ->merchantRefNum->toBe($payment->merchant_ref_number)
        ->customerMobile->toBe('01012345678')
        ->customerEmail->toBe('ahmed@example.com')
        ->customerProfileId->toBe((string) $booking->id)
        ->orderWebHookUrl->toBe(route('payment.webhook'))
        ->returnUrl->toBe(route('payment.callback'))
        ->and($payload['chargeItems'][0])
        ->itemId->toBe((string) $package->id)
        ->price->toEqual(3000.0)
        ->quantity->toBe(1);

    expect($payload['signature'])->toBe(fawryCheckoutSignature([
        'merchantCode' => 'TEST-MERCHANT',
        'merchantRefNum' => $payment->merchant_ref_number,
        'customerProfileId' => (string) $booking->id,
        'returnUrl' => route('payment.callback'),
        'items' => [
            [
                'id' => (string) $package->id,
                'quantity' => 1,
                'price' => 3000,
            ],
        ],
    ], 'secret'));
});

test('fawry checkout requires configuration before creating a booking', function () {
    config([
        'fawry.merchant_code' => null,
        'fawry.secure_key' => null,
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

    expect(Booking::count())->toBe(0)
        ->and(Payment::count())->toBe(0);
});

test('fawry webhook marks booking paid and queues confirmation email', function () {
    config([
        'fawry.secure_key' => 'secret',
    ]);
    Mail::fake();

    [$booking, $payment] = paidTestBooking();
    $payload = fawryPaidPayload($booking, $payment);
    $payload['signature'] = fawryReturnSignature($payload, 'secret');

    $this->post(route('payment.webhook'), $payload)
        ->assertSuccessful()
        ->assertContent('');

    expect($payment->refresh())
        ->status->toBe(PaymentStatus::Paid)
        ->fawry_reference_number->toBe('1122334455')
        ->and($booking->refresh())
        ->status->toBe(BookingStatus::Paid)
        ->paid_at->not->toBeNull()
        ->payment_success_email_sent_at->not->toBeNull();

    Mail::assertQueued(BookingPaymentSucceeded::class);
});

test('fawry callback marks booking paid and redirects to booking result', function () {
    config([
        'fawry.secure_key' => 'secret',
    ]);
    Mail::fake();

    [$booking, $payment] = paidTestBooking();
    $payload = fawryPaidPayload($booking, $payment);
    $payload['signature'] = fawryReturnSignature($payload, 'secret');

    $this->get(route('payment.callback', $payload))
        ->assertRedirect(route('bookings.result', ['booking' => $booking]));

    expect($payment->refresh())
        ->status->toBe(PaymentStatus::Paid)
        ->fawry_reference_number->toBe('1122334455')
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
 * @return array{0: Booking, 1: Payment}
 */
function paidTestBooking(): array
{
    $booking = Booking::factory()->create([
        'status' => BookingStatus::AwaitingPayment,
        'type' => PackageOrderAction::FawryPayment,
        'total_amount' => 3000,
        'currency' => 'EGP',
    ]);
    $payment = Payment::factory()->create([
        'booking_id' => $booking->id,
        'merchant_ref_number' => 'BK-1-20260519190000',
        'status' => PaymentStatus::Pending,
        'amount' => 3000,
        'currency' => 'EGP',
        'payment_method' => 'FawryPayCheckoutButton',
    ]);

    return [$booking, $payment];
}

/**
 * @return array<string, string>
 */
function fawryPaidPayload(Booking $booking, Payment $payment): array
{
    return [
        'referenceNumber' => '1122334455',
        'merchantRefNumber' => $payment->merchant_ref_number,
        'paymentAmount' => '3000.00',
        'orderAmount' => '3000.00',
        'orderStatus' => 'PAID',
        'paymentMethod' => 'CARD',
        'fawryFees' => '0.00',
        'customerMail' => $booking->customer_email,
        'customerMobile' => $booking->customer_mobile,
    ];
}

/**
 * @param  array{merchantCode: string, merchantRefNum: string, customerProfileId: string, returnUrl: string, items: array<int, array{id: string, quantity: int, price: int|float}>}  $payload
 */
function fawryCheckoutSignature(array $payload, string $secureKey): string
{
    $items = $payload['items'];
    usort($items, fn (array $a, array $b): int => strcmp((string) $a['id'], (string) $b['id']));

    $signaturePayload = $payload['merchantCode']
        .$payload['merchantRefNum']
        .$payload['customerProfileId']
        .$payload['returnUrl'];

    foreach ($items as $item) {
        $signaturePayload .= $item['id'].$item['quantity'].number_format((float) $item['price'], 2, '.', '');
    }

    return hash('sha256', $signaturePayload.$secureKey);
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
