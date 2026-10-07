<?php

use App\Enum\BookingSource;
use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Enum\PaymentStatus;
use App\Mail\ApiBookingReceived;
use App\Models\ApiClient;
use App\Models\Booking;
use App\Models\HotelRoom;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
});

test('a partner that collects payment creates a pending package booking at its marked up price', function () {
    $client = ApiClient::factory()->withMarkup(10)->create();
    Sanctum::actingAs($client, ['*']);
    $package = Package::factory()->create(['slug' => 'gold', 'price' => 2000]);

    $response = $this->postJson(route('api.v1.bookings.store'), [
        ...apiCustomer(),
        'type' => 'package',
        'package' => 'gold',
        'guests' => 2,
        'external_reference' => 'PARTNER-77',
    ], ['Idempotency-Key' => 'key-1'])
        ->assertCreated()
        ->assertJsonPath('data.status', 'pending')
        ->assertJsonPath('data.kind', 'package')
        ->assertJsonPath('data.package.slug', 'gold')
        ->assertJsonPath('data.total_amount', 2200)
        ->assertJsonPath('data.external_reference', 'PARTNER-77')
        ->assertJsonMissingPath('data.payment_url');

    $booking = Booking::sole();

    expect($booking)
        ->package_id->toBe($package->id)
        ->source->toBe(BookingSource::Api)
        ->api_client_id->toBe($client->id)
        ->status->toBe(BookingStatus::Pending)
        ->type->toBe(PackageOrderAction::CustomForm)
        ->total_amount->toBe('2200.00')
        ->reference->toStartWith('KD-')
        ->and($response->json('data.reference'))->toBe($booking->reference);

    Mail::assertQueued(ApiBookingReceived::class, fn (ApiBookingReceived $mail): bool => $mail->booking->is($booking));
});

test('a fawry partner gets a payment link for the booking', function () {
    config(['fawry.merchant_code' => 'TEST-MERCHANT', 'fawry.secure_key' => 'secret', 'fawry.mode' => 'sandbox']);
    Sanctum::actingAs(ApiClient::factory()->fawryPayment()->create(), ['*']);
    Package::factory()->create(['slug' => 'gold', 'price' => 3000]);

    $this->postJson(route('api.v1.bookings.store'), [...apiCustomer(), 'type' => 'package', 'package' => 'gold'], ['Idempotency-Key' => 'key-1'])
        ->assertCreated()
        ->assertJsonPath('data.status', 'awaiting_payment');

    $booking = Booking::query()->with('payment')->sole();

    expect($booking->payment)
        ->status->toBe(PaymentStatus::Pending)
        ->amount->toBe('3000.00');

    $this->getJson(route('api.v1.bookings.show', $booking->reference))
        ->assertJsonPath('data.payment_url', route('payment.checkout', ['locale' => 'en', 'booking' => $booking]));
});

test('a fawry partner booking is refused while fawry is not configured', function () {
    config(['fawry.merchant_code' => null, 'fawry.secure_key' => null]);
    Sanctum::actingAs(ApiClient::factory()->fawryPayment()->create(), ['*']);
    Package::factory()->create(['slug' => 'gold']);

    $this->postJson(route('api.v1.bookings.store'), [...apiCustomer(), 'type' => 'package', 'package' => 'gold'], ['Idempotency-Key' => 'key-1'])
        ->assertServiceUnavailable();

    expect(Booking::count())->toBe(0);
});

test('a hotel room booking holds the rooms for a partner that collects payment', function () {
    Sanctum::actingAs(ApiClient::factory()->withMarkup(10)->create(), ['*']);
    $room = HotelRoom::factory()->create(['price_per_night' => 1000, 'quantity' => 2, 'max_adults' => 2]);

    $this->postJson(route('api.v1.bookings.store'), [
        ...apiCustomer(),
        ...apiHotelStay($room, ['rooms' => 2, 'adults' => 4]),
    ], ['Idempotency-Key' => 'key-1'])
        ->assertCreated()
        ->assertJsonPath('data.kind', 'hotel_room')
        ->assertJsonPath('data.hotel.room_id', $room->id)
        ->assertJsonPath('data.hotel.nights', 3)
        ->assertJsonPath('data.total_amount', 6600);

    expect(Booking::sole())
        ->status->toBe(BookingStatus::Pending)
        ->source->toBe(BookingSource::Api);

    $this->postJson(route('api.v1.bookings.store'), [
        ...apiCustomer(),
        ...apiHotelStay($room, ['rooms' => 1, 'adults' => 1]),
    ], ['Idempotency-Key' => 'key-2'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('rooms');
});

test('repeating a booking request with the same idempotency key returns the first booking', function () {
    Sanctum::actingAs(ApiClient::factory()->create(), ['*']);
    $room = HotelRoom::factory()->create(['quantity' => 1]);
    $payload = [...apiCustomer(), ...apiHotelStay($room)];

    $first = $this->postJson(route('api.v1.bookings.store'), $payload, ['Idempotency-Key' => 'same-key'])->assertCreated();

    $this->postJson(route('api.v1.bookings.store'), $payload, ['Idempotency-Key' => 'same-key'])
        ->assertSuccessful()
        ->assertHeader('Idempotent-Replayed', 'true')
        ->assertJsonPath('data.reference', $first->json('data.reference'));

    expect(Booking::count())->toBe(1);
    Mail::assertQueuedCount(1);
});

test('creating a booking requires an idempotency key', function () {
    Sanctum::actingAs(ApiClient::factory()->create(), ['*']);
    Package::factory()->create(['slug' => 'gold']);

    $this->postJson(route('api.v1.bookings.store'), [...apiCustomer(), 'type' => 'package', 'package' => 'gold'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('idempotency_key');
});

test('booking requests are validated', function (array $payload, array $errors) {
    Sanctum::actingAs(ApiClient::factory()->create(), ['*']);
    Package::factory()->create(['slug' => 'hidden', 'is_active' => false]);

    $this->postJson(route('api.v1.bookings.store'), $payload, ['Idempotency-Key' => 'key-1'])
        ->assertUnprocessable()
        ->assertJsonValidationErrors($errors);
})->with([
    'missing type and customer' => [[], ['type', 'customer_name', 'customer_email', 'customer_mobile']],
    'inactive package' => [['type' => 'package', 'package' => 'hidden'], ['package']],
    'hotel room without a stay' => [['type' => 'hotel_room'], ['hotel_room_id', 'check_in', 'check_out', 'rooms', 'adults']],
]);

test('a partner can only see and cancel its own bookings', function () {
    $client = ApiClient::factory()->create();
    $own = Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => $client->id]);
    $other = Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => ApiClient::factory()->create()->id]);
    Sanctum::actingAs($client, ['*']);

    $this->getJson(route('api.v1.bookings.show', $own->reference))
        ->assertSuccessful()
        ->assertJsonPath('data.reference', $own->reference);

    $this->getJson(route('api.v1.bookings.show', $other->reference))->assertNotFound();
    $this->postJson(route('api.v1.bookings.cancel', $other->reference))->assertNotFound();
});

test('cancelling an unpaid booking cancels its pending payment too', function () {
    $client = ApiClient::factory()->create();
    $booking = Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => $client->id, 'status' => BookingStatus::AwaitingPayment]);
    $booking->payment()->create(['provider' => 'fawry', 'merchant_ref_number' => 'BK-1', 'status' => PaymentStatus::Pending, 'amount' => 100, 'currency' => 'EGP']);
    Sanctum::actingAs($client, ['*']);

    $this->postJson(route('api.v1.bookings.cancel', $booking->reference))
        ->assertSuccessful()
        ->assertJsonPath('data.status', 'cancelled');

    expect($booking->payment->fresh()->status)->toBe(PaymentStatus::Cancelled);
});

test('a paid booking cannot be cancelled through the api', function () {
    $client = ApiClient::factory()->create();
    $booking = Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => $client->id, 'status' => BookingStatus::Paid]);
    Sanctum::actingAs($client, ['*']);

    $this->postJson(route('api.v1.bookings.cancel', $booking->reference))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('status');

    expect($booking->fresh()->status)->toBe(BookingStatus::Paid);
});

test('website bookings still get a reference and the web source', function () {
    $booking = Booking::factory()->create();

    expect($booking)
        ->reference->toStartWith('KD-')
        ->source->toBe(BookingSource::Web)
        ->api_client_id->toBeNull();
});

/**
 * @return array<string, string>
 */
function apiCustomer(): array
{
    return [
        'customer_name' => 'Sara Ali',
        'customer_email' => 'sara@example.com',
        'customer_mobile' => '01012345678',
    ];
}

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function apiHotelStay(HotelRoom $room, array $overrides = []): array
{
    return [
        'type' => 'hotel_room',
        'hotel_room_id' => $room->id,
        'check_in' => now()->addDays(10)->toDateString(),
        'check_out' => now()->addDays(13)->toDateString(),
        'rooms' => 1,
        'adults' => 1,
        'children' => 0,
        ...$overrides,
    ];
}
