<?php

use App\Enum\BookingStatus;
use App\Mail\BookingPaymentSucceeded;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('hotels page lists active hotels with their lowest room price', function () {
    $hotel = Hotel::factory()->create(['name' => ['en' => 'Nile View Hotel']]);
    HotelRoom::factory()->for($hotel)->create(['price_per_night' => 2400]);
    HotelRoom::factory()->for($hotel)->create(['price_per_night' => 1800]);
    HotelRoom::factory()->for($hotel)->inactive()->create(['price_per_night' => 900]);
    Hotel::factory()->inactive()->create(['name' => ['en' => 'Hidden Hotel']]);

    $this->get(route('hotels'))
        ->assertSuccessful()
        ->assertSee('Nile View Hotel')
        ->assertSee('EGP 1,800')
        ->assertDontSee('EGP 900')
        ->assertDontSee('Hidden Hotel');
});

test('hotels page filters by destination and stars', function () {
    $cairo = Destination::factory()->create(['slug' => 'cairo']);
    Hotel::factory()->for($cairo)->create(['name' => ['en' => 'Cairo Five'], 'stars' => 5]);
    Hotel::factory()->for($cairo)->create(['name' => ['en' => 'Cairo Three'], 'stars' => 3]);
    Hotel::factory()->create(['name' => ['en' => 'Other Five'], 'stars' => 5]);

    $this->get(route('hotels', ['destination' => 'cairo', 'stars' => 5]))
        ->assertSuccessful()
        ->assertSee('Cairo Five')
        ->assertDontSee('Cairo Three')
        ->assertDontSee('Other Five');
});

test('hotel page shows rooms and the availability form', function () {
    $hotel = Hotel::factory()->create([
        'name' => ['en' => 'Red Sea Resort'],
        'policies' => ['en' => [['title' => 'Pets', 'description' => 'Pets are not allowed.']]],
        'cancellation_policy' => ['en' => 'Free cancellation up to 48 hours before arrival.'],
    ]);
    $room = HotelRoom::factory()->for($hotel)->create(['name' => ['en' => 'Sea View Suite'], 'price_per_night' => 3200]);
    HotelRoom::factory()->for($hotel)->inactive()->create(['name' => ['en' => 'Closed Room']]);

    $this->get(route('hotels.show', ['hotel' => $hotel->slug]))
        ->assertSuccessful()
        ->assertSee('Red Sea Resort')
        ->assertSee('Sea View Suite')
        ->assertSee('EGP 3,200')
        ->assertSee('Free Wi-Fi')
        ->assertSee('Pets are not allowed.')
        ->assertSee('Free cancellation up to 48 hours before arrival.')
        ->assertSee('value="'.$room->id.'"', false)
        ->assertSee(route('hotels.book', ['hotel' => $hotel->slug]), false)
        ->assertDontSee('Closed Room');
});

test('inactive hotels are not found', function () {
    $hotel = Hotel::factory()->inactive()->create();

    $this->get(route('hotels.show', ['hotel' => $hotel->slug]))->assertNotFound();
});

test('hotels are linked in the navigation and listed in the sitemap', function () {
    $hotel = Hotel::factory()->create();

    $this->get(route('hotels'))
        ->assertSee(__('nav.hotels'));

    $this->get(route('sitemap'))
        ->assertSuccessful()
        ->assertSee(route('hotels', ['locale' => 'en']), false)
        ->assertSee(route('hotels.show', ['locale' => 'en', 'hotel' => $hotel->slug]), false);
});

test('booking result page and payment email describe the hotel stay', function () {
    $room = HotelRoom::factory()->create(['name' => ['en' => 'Deluxe Room']]);
    $booking = Booking::factory()->create([
        'package_id' => null,
        'hotel_id' => $room->hotel_id,
        'hotel_room_id' => $room->id,
        'check_in' => now()->addDays(5),
        'check_out' => now()->addDays(7),
        'nights' => 2,
        'rooms' => 1,
        'status' => BookingStatus::Paid,
    ]);

    $this->get(route('bookings.result', ['booking' => $booking]))
        ->assertSuccessful()
        ->assertSee($room->hotel->name.' - Deluxe Room')
        ->assertSee(route('hotels.show', ['hotel' => $room->hotel->slug]), false);

    (new BookingPaymentSucceeded($booking))
        ->assertHasSubject(__('packages.booking.mail_subject', ['package' => $booking->bookedItemName()]))
        ->assertSeeInHtml('Deluxe Room');
});

test('home page shows featured active hotels', function () {
    $hotel = Hotel::factory()->create(['name' => ['en' => 'Luxor Palace'], 'sort_order' => 1]);
    HotelRoom::factory()->for($hotel)->create(['price_per_night' => 2750]);
    Hotel::factory()->inactive()->create(['name' => ['en' => 'Hidden Hotel']]);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee(__('home.hotels.title'))
        ->assertSee('Luxor Palace')
        ->assertSee('EGP 2,750')
        ->assertSee(route('hotels.show', ['hotel' => $hotel->slug]), false)
        ->assertSee(__('home.common.view_all_hotels'))
        ->assertDontSee('Hidden Hotel');
});

test('home page hides the hotels section when there are no active hotels', function () {
    Hotel::factory()->inactive()->create();

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertDontSee(__('home.hotels.title'));
});
