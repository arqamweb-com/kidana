<?php

use App\Enum\PackageOrderAction;
use App\Enum\Role;
use App\Filament\Resources\Bookings\Pages\ListBookings;
use App\Filament\Resources\Hotel\HotelResource;
use App\Filament\Resources\Hotel\Pages\CreateHotel;
use App\Filament\Resources\Hotel\Pages\EditHotel;
use App\Filament\Resources\Hotel\Pages\ListHotels;
use App\Filament\Resources\Hotel\RelationManagers\RoomsRelationManager;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => Role::Editor]));
});

test('editors can access the hotels resource', function () {
    expect(HotelResource::canAccess())->toBeTrue();

    $this->get(HotelResource::getUrl('index'))->assertSuccessful();
});

test('hotels list shows hotels', function () {
    $hotels = Hotel::factory()->count(2)->create();

    Livewire::test(ListHotels::class)
        ->assertCanSeeTableRecords($hotels);
});

test('a hotel can be created from the dashboard', function () {
    $destination = Destination::factory()->create();

    Livewire::test(CreateHotel::class)
        ->fillForm([
            'name' => 'Pyramids Hotel',
            'slug' => 'pyramids-hotel',
            'destination_id' => $destination->id,
            'stars' => 5,
            'order_action' => PackageOrderAction::FawryPayment->value,
            'is_active' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $hotel = Hotel::sole();

    expect($hotel)
        ->slug->toBe('pyramids-hotel')
        ->stars->toBe(5)
        ->order_action->toBe(PackageOrderAction::FawryPayment)
        ->and($hotel->getTranslation('name', 'en'))->toBe('Pyramids Hotel');
});

test('the hotel edit page shows the room types manager', function () {
    $hotel = Hotel::factory()->create();

    Livewire::test(EditHotel::class, ['record' => $hotel->getRouteKey()])
        ->assertSeeLivewire(RoomsRelationManager::class);
});

test('room types can be added to a hotel', function () {
    $hotel = Hotel::factory()->create();

    Livewire::test(RoomsRelationManager::class, [
        'ownerRecord' => $hotel,
        'pageClass' => EditHotel::class,
        'activeLocale' => 'en',
    ])
        ->callAction(TestAction::make(CreateAction::class)->table(), [
            'name' => 'Family Suite',
            'price_per_night' => 4200,
            'quantity' => 3,
            'max_adults' => 4,
            'max_children' => 2,
            'is_active' => true,
        ])
        ->assertHasNoFormErrors();

    $room = HotelRoom::sole();

    expect($room)
        ->hotel_id->toBe($hotel->id)
        ->price_per_night->toBe(4200)
        ->quantity->toBe(3)
        ->and($room->getTranslation('name', 'en'))->toBe('Family Suite');
});

test('bookings list can be filtered to hotel bookings', function () {
    $room = HotelRoom::factory()->create();
    $hotelBooking = Booking::factory()->create([
        'package_id' => null,
        'hotel_id' => $room->hotel_id,
        'hotel_room_id' => $room->id,
        'check_in' => now()->addDays(3),
        'check_out' => now()->addDays(5),
    ]);
    $packageBooking = Booking::factory()->create();

    $this->actingAs(User::factory()->create(['role' => Role::Admin]));

    Livewire::test(ListBookings::class)
        ->assertCanSeeTableRecords([$hotelBooking, $packageBooking])
        ->filterTable('hotel_bookings', true)
        ->assertCanSeeTableRecords([$hotelBooking])
        ->assertCanNotSeeTableRecords([$packageBooking]);
});
