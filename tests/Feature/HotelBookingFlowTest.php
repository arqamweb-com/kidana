<?php

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Enum\PaymentStatus;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('booking page shows the stay summary with the total for every night and room', function () {
    $room = HotelRoom::factory()->create(['price_per_night' => 1500, 'max_adults' => 2]);

    $this->get(route('hotels.book', ['hotel' => $room->hotel->slug, ...hotelStay($room, ['rooms' => 2, 'adults' => 3])]))
        ->assertSuccessful()
        ->assertSee($room->hotel->name)
        ->assertSee('EGP 9,000.00')
        ->assertSee(route('hotels.book.store', ['hotel' => $room->hotel->slug]), false);
});

test('custom request hotel booking stores a pending booking with the stay details', function () {
    $room = HotelRoom::factory()->create(['price_per_night' => 2000]);

    $this->post(route('hotels.book.store', ['hotel' => $room->hotel->slug]), [
        ...hotelStay($room, ['rooms' => 1, 'adults' => 2, 'children' => 1]),
        ...hotelCustomer(),
    ])
        ->assertSuccessful()
        ->assertSee(__('packages.booking.request_received_title'))
        ->assertSee(__('hotels.booking.back_to_hotel'));

    $booking = Booking::sole();

    expect($booking)
        ->hotel_id->toBe($room->hotel_id)
        ->hotel_room_id->toBe($room->id)
        ->package_id->toBeNull()
        ->status->toBe(BookingStatus::Pending)
        ->type->toBe(PackageOrderAction::CustomForm)
        ->nights->toBe(3)
        ->rooms->toBe(1)
        ->adults->toBe(2)
        ->children->toBe(1)
        ->guests->toBe(3)
        ->total_amount->toBe('6000.00')
        ->and($booking->check_in->toDateString())->toBe(now()->addDays(10)->toDateString())
        ->and($booking->check_out->toDateString())->toBe(now()->addDays(13)->toDateString());
});

test('fawry hotel booking creates a pending payment and opens the fawry checkout', function () {
    config([
        'fawry.merchant_code' => 'TEST-MERCHANT',
        'fawry.secure_key' => 'secret',
        'fawry.mode' => 'sandbox',
    ]);

    $hotel = Hotel::factory()->fawryPayment()->create();
    $room = HotelRoom::factory()->for($hotel)->create(['price_per_night' => 1000]);

    $response = $this->post(route('hotels.book.store', ['hotel' => $hotel->slug]), [
        ...hotelStay($room, ['rooms' => 2, 'adults' => 4]),
        ...hotelCustomer(),
    ]);

    $booking = Booking::query()->with('payment')->sole();

    $response->assertRedirect(route('payment.checkout', ['booking' => $booking]));

    expect($booking)
        ->status->toBe(BookingStatus::AwaitingPayment)
        ->type->toBe(PackageOrderAction::FawryPayment)
        ->total_amount->toBe('6000.00')
        ->and($booking->payment)
        ->status->toBe(PaymentStatus::Pending)
        ->amount->toBe('6000.00');

    $this->get(route('payment.checkout', ['booking' => $booking]))
        ->assertSuccessful()
        ->assertSee('FawryPay.checkout', false);

    expect($booking->payment->refresh()->request_payload['chargeItems'][0])
        ->itemId->toBe((string) $room->id)
        ->description->toBe($booking->bookedItemName());
});

test('the total is calculated on the server and ignores a submitted amount', function () {
    $room = HotelRoom::factory()->create(['price_per_night' => 500]);

    $this->post(route('hotels.book.store', ['hotel' => $room->hotel->slug]), [
        ...hotelStay($room),
        ...hotelCustomer(),
        'total_amount' => 1,
    ])->assertSuccessful();

    expect(Booking::sole()->total_amount)->toBe('1500.00');
});

test('invalid stays are rejected', function (array $overrides, string $errorField) {
    $room = HotelRoom::factory()->create(['max_adults' => 2, 'max_children' => 1]);

    $this->post(route('hotels.book.store', ['hotel' => $room->hotel->slug]), [
        ...hotelStay($room, $overrides),
        ...hotelCustomer(),
    ])->assertSessionHasErrors($errorField);

    expect(Booking::count())->toBe(0);
})->with([
    'check-in in the past' => [['check_in' => now()->subDay()->toDateString()], 'check_in'],
    'check-out before check-in' => [['check_out' => now()->addDays(9)->toDateString()], 'check_out'],
    'same day check-out' => [['check_out' => now()->addDays(10)->toDateString()], 'check_out'],
    'stay longer than the limit' => [['check_out' => now()->addDays(41)->toDateString()], 'check_out'],
    'too many adults for the rooms' => [['adults' => 3], 'adults'],
    'too many children for the rooms' => [['children' => 2], 'children'],
    'more rooms than adults' => [['rooms' => 2, 'adults' => 1], 'adults'],
    'no room selected' => [['room_id' => null], 'room_id'],
]);

test('a room from another hotel cannot be booked', function () {
    $hotel = Hotel::factory()->create();
    $otherRoom = HotelRoom::factory()->create();

    $this->post(route('hotels.book.store', ['hotel' => $hotel->slug]), [
        ...hotelStay($otherRoom),
        ...hotelCustomer(),
    ])->assertSessionHasErrors('room_id');
});

test('inactive rooms cannot be booked', function () {
    $room = HotelRoom::factory()->inactive()->create();

    $this->post(route('hotels.book.store', ['hotel' => $room->hotel->slug]), [
        ...hotelStay($room),
        ...hotelCustomer(),
    ])->assertSessionHasErrors('room_id');
});

test('rooms that are fully booked for the dates cannot be booked again', function () {
    $room = HotelRoom::factory()->create(['quantity' => 2]);

    Booking::factory()->create([
        'package_id' => null,
        'hotel_id' => $room->hotel_id,
        'hotel_room_id' => $room->id,
        'check_in' => now()->addDays(11),
        'check_out' => now()->addDays(12),
        'rooms' => 2,
        'status' => BookingStatus::Paid,
    ]);

    $this->post(route('hotels.book.store', ['hotel' => $room->hotel->slug]), [
        ...hotelStay($room),
        ...hotelCustomer(),
    ])->assertSessionHasErrors(['rooms' => __('hotels.booking.errors.not_available')]);
});

test('room availability counts only the busiest night of the stay', function () {
    $room = HotelRoom::factory()->create(['quantity' => 3]);

    $bookingAttributes = [
        'package_id' => null,
        'hotel_id' => $room->hotel_id,
        'hotel_room_id' => $room->id,
        'status' => BookingStatus::Paid,
    ];

    Booking::factory()->create([...$bookingAttributes, 'check_in' => now()->addDays(10), 'check_out' => now()->addDays(11), 'rooms' => 2]);
    Booking::factory()->create([...$bookingAttributes, 'check_in' => now()->addDays(12), 'check_out' => now()->addDays(13), 'rooms' => 1]);
    Booking::factory()->create([...$bookingAttributes, 'check_in' => now()->addDays(11), 'check_out' => now()->addDays(12), 'rooms' => 3, 'status' => BookingStatus::Cancelled]);
    Booking::factory()->create([...$bookingAttributes, 'check_in' => now()->addDays(11), 'check_out' => now()->addDays(12), 'rooms' => 3, 'status' => BookingStatus::AwaitingPayment, 'created_at' => now()->subHours(3)]);

    expect($room->availableRoomsBetween(now()->addDays(10)->startOfDay(), now()->addDays(13)->startOfDay()))->toBe(1)
        ->and($room->availableRoomsBetween(now()->addDays(13)->startOfDay(), now()->addDays(15)->startOfDay()))->toBe(3);
});

test('unpaid fawry checkouts hold their rooms for a limited time', function () {
    $room = HotelRoom::factory()->create(['quantity' => 1]);

    Booking::factory()->create([
        'package_id' => null,
        'hotel_id' => $room->hotel_id,
        'hotel_room_id' => $room->id,
        'check_in' => now()->addDays(10),
        'check_out' => now()->addDays(12),
        'rooms' => 1,
        'status' => BookingStatus::AwaitingPayment,
    ]);

    expect($room->availableRoomsBetween(now()->addDays(10)->startOfDay(), now()->addDays(12)->startOfDay()))->toBe(0);

    $this->travel(HotelRoom::PENDING_PAYMENT_HOLD_MINUTES + 1)->minutes();

    expect($room->availableRoomsBetween(now()->addDays(10)->startOfDay(), now()->addDays(12)->startOfDay()))->toBe(1);
});

test('invalid stay details on the booking page send the visitor back to the hotel', function () {
    $room = HotelRoom::factory()->create();

    $this->get(route('hotels.book', ['hotel' => $room->hotel->slug, ...hotelStay($room, ['check_out' => now()->addDays(5)->toDateString()])]))
        ->assertRedirect(route('hotels.show', ['hotel' => $room->hotel->slug]).'#availability')
        ->assertSessionHasErrors('check_out');
});

test('inactive hotels cannot be booked', function () {
    $hotel = Hotel::factory()->inactive()->create();
    $room = HotelRoom::factory()->for($hotel)->create();

    $this->get(route('hotels.book', ['hotel' => $hotel->slug, ...hotelStay($room)]))
        ->assertNotFound();
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function hotelStay(HotelRoom $room, array $overrides = []): array
{
    return [
        'room_id' => $room->id,
        'check_in' => now()->addDays(10)->toDateString(),
        'check_out' => now()->addDays(13)->toDateString(),
        'rooms' => 1,
        'adults' => 2,
        'children' => 0,
        ...$overrides,
    ];
}

/**
 * @return array<string, string>
 */
function hotelCustomer(): array
{
    return [
        'customer_name' => 'Ahmed Ali',
        'customer_email' => 'ahmed@example.com',
        'customer_mobile' => '01012345678',
        'message' => 'Late arrival',
    ];
}
