<?php

use App\Enum\ApiAbility;
use App\Enum\BookingSource;
use App\Enum\BookingStatus;
use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\HotelRoom;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('the partner api rejects requests without a token', function () {
    $this->getJson(route('api.v1.services.index'))->assertUnauthorized();
});

test('the partner api answers json 401 even when the request does not ask for json', function () {
    $this->get(route('api.v1.services.index'))
        ->assertUnauthorized()
        ->assertJson(['message' => 'Unauthenticated.']);
});

test('a real partner token can read the catalog and the call is logged', function () {
    $client = ApiClient::factory()->create();
    $token = $client->createToken('integration', [ApiAbility::CatalogRead->value])->plainTextToken;
    Service::factory()->create(['slug' => 'umrah']);

    $this->withToken($token)
        ->getJson(route('api.v1.services.index'))
        ->assertSuccessful()
        ->assertJsonPath('data.0.slug', 'umrah');

    expect(ApiRequestLog::sole())
        ->api_client_id->toBe($client->id)
        ->method->toBe('GET')
        ->path->toBe('/api/v1/services')
        ->status->toBe(200);
});

test('a token without the needed ability is refused', function () {
    $client = ApiClient::factory()->create();
    $token = $client->createToken('bookings-only', [ApiAbility::BookingsRead->value])->plainTextToken;

    $this->withToken($token)->getJson(route('api.v1.packages.index'))->assertForbidden();
});

test('removing an ability from the client revokes it on tokens issued before', function () {
    $client = ApiClient::factory()->create();
    $token = $client->createToken('all', ApiAbility::values())->plainTextToken;

    $client->update(['allowed_abilities' => [ApiAbility::BookingsRead->value]]);

    $this->withToken($token)->getJson(route('api.v1.services.index'))->assertForbidden();
});

test('inactive clients and clients outside the ip allowlist are blocked', function (array $state) {
    Sanctum::actingAs(ApiClient::factory()->create($state), ['*']);

    $this->getJson(route('api.v1.services.index'))->assertForbidden();
})->with([
    'inactive' => [['is_active' => false]],
    'ip not allowed' => [['ip_allowlist' => ['10.0.0.0/8']]],
]);

test('an allowlisted ip may call the api', function () {
    Sanctum::actingAs(ApiClient::factory()->create(['ip_allowlist' => ['127.0.0.1']]), ['*']);

    $this->getJson(route('api.v1.services.index'))->assertSuccessful();
});

test('packages show prices with the partner markup and can be filtered', function () {
    Sanctum::actingAs(ApiClient::factory()->withMarkup(10)->create(), ['*']);

    $service = Service::factory()->create(['slug' => 'umrah']);
    $destination = Destination::factory()->create(['slug' => 'mecca']);
    Package::factory()->for($service)->for($destination)->create(['slug' => 'umrah-gold', 'price' => 1000]);
    Package::factory()->for($service)->create(['slug' => 'elsewhere', 'price' => 1000]);
    Package::factory()->for($destination)->create(['slug' => 'other-service', 'price' => 1000]);
    Package::factory()->for($service)->for($destination)->create(['slug' => 'too-expensive', 'price' => 5000]);
    Package::factory()->for($service)->for($destination)->create(['slug' => 'hidden', 'price' => 1000, 'is_active' => false]);

    $this->getJson(route('api.v1.packages.index', ['service' => 'umrah', 'destination' => 'mecca', 'max_price' => 1100]))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.slug', 'umrah-gold')
        ->assertJsonPath('data.0.price', 1100)
        ->assertJsonPath('data.0.service.slug', 'umrah')
        ->assertJsonPath('data.0.destination.slug', 'mecca');
});

test('package details are translated from the accept-language header', function () {
    Sanctum::actingAs(ApiClient::factory()->create(), ['*']);
    Package::factory()->create(['slug' => 'gold', 'name' => ['en' => 'Gold Umrah', 'ar' => 'عمرة ذهبية']]);

    $this->getJson(route('api.v1.packages.show', 'gold'), ['Accept-Language' => 'ar'])
        ->assertSuccessful()
        ->assertHeader('Content-Language', 'ar')
        ->assertJsonPath('data.name', 'عمرة ذهبية')
        ->assertJsonStructure(['data' => ['itinerary', 'included_items', 'gallery']]);

    $this->getJson(route('api.v1.packages.show', 'gold'))
        ->assertJsonPath('data.name', 'Gold Umrah');
});

test('inactive packages are not found', function () {
    Sanctum::actingAs(ApiClient::factory()->create(), ['*']);
    Package::factory()->create(['slug' => 'hidden', 'is_active' => false]);

    $this->getJson(route('api.v1.packages.show', 'hidden'))->assertNotFound();
});

test('hotel details list the active rooms with marked up prices', function () {
    Sanctum::actingAs(ApiClient::factory()->withMarkup(20)->create(), ['*']);
    $room = HotelRoom::factory()->create(['price_per_night' => 1000]);
    HotelRoom::factory()->for($room->hotel)->inactive()->create();

    $this->getJson(route('api.v1.hotels.show', $room->hotel->slug))
        ->assertSuccessful()
        ->assertJsonCount(1, 'data.rooms')
        ->assertJsonPath('data.rooms.0.id', $room->id)
        ->assertJsonPath('data.rooms.0.price_per_night', 1200);
});

test('hotel availability counts rooms held by paid and partner bookings', function () {
    Sanctum::actingAs($client = ApiClient::factory()->create(), ['*']);
    $room = HotelRoom::factory()->create(['price_per_night' => 1000, 'quantity' => 3, 'max_adults' => 2]);
    $checkIn = now()->addDays(10)->toDateString();
    $checkOut = now()->addDays(12)->toDateString();

    $stay = ['package_id' => null, 'hotel_id' => $room->hotel_id, 'hotel_room_id' => $room->id, 'check_in' => $checkIn, 'check_out' => $checkOut, 'rooms' => 1];
    Booking::factory()->create([...$stay, 'status' => BookingStatus::Paid]);
    Booking::factory()->create([...$stay, 'status' => BookingStatus::Pending, 'source' => BookingSource::Api, 'api_client_id' => $client->id]);
    Booking::factory()->create([...$stay, 'status' => BookingStatus::Pending]);

    $this->getJson(route('api.v1.hotels.availability', [$room->hotel->slug, 'check_in' => $checkIn, 'check_out' => $checkOut, 'adults' => 3]))
        ->assertSuccessful()
        ->assertJsonPath('data.nights', 2)
        ->assertJsonPath('data.rooms.0.available_rooms', 1)
        ->assertJsonPath('data.rooms.0.rooms_needed', 2)
        ->assertJsonPath('data.rooms.0.is_available', false)
        ->assertJsonPath('data.rooms.0.total_for_stay', 4000);
});

test('hotel availability validates the dates', function () {
    Sanctum::actingAs(ApiClient::factory()->create(), ['*']);
    $room = HotelRoom::factory()->create();

    $this->getJson(route('api.v1.hotels.availability', [$room->hotel->slug, 'check_in' => now()->subDay()->toDateString()]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['check_in', 'check_out']);
});
