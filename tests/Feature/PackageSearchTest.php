<?php

use App\Models\Destination;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page includes the package search form', function () {
    $destination = Destination::factory()->create([
        'name' => ['en' => 'Mecca'],
        'slug' => 'mecca',
    ]);

    Package::factory()->create([
        'destination_id' => $destination->id,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee(route('packages.search'), false);
    $response->assertSee('name="destination"', false);
    $response->assertSee('name="travel_date"', false);
    $response->assertSee('name="guests"', false);
    $response->assertSee('Mecca');
});

test('package search returns only matching active packages', function () {
    $mecca = Destination::factory()->create([
        'name' => ['en' => 'Mecca'],
        'slug' => 'mecca',
    ]);
    $istanbul = Destination::factory()->create([
        'name' => ['en' => 'Istanbul'],
        'slug' => 'istanbul',
    ]);

    $matchingPackage = Package::factory()->create([
        'name' => 'Luxury Umrah Package',
        'destination_id' => $mecca->id,
        'start_date' => '2026-06-10',
        'end_date' => '2026-06-20',
        'max_guests' => 4,
        'is_active' => true,
    ]);

    Package::factory()->create([
        'name' => 'Inactive Mecca Package',
        'destination_id' => $mecca->id,
        'start_date' => '2026-06-10',
        'end_date' => '2026-06-20',
        'max_guests' => 4,
        'is_active' => false,
    ]);

    Package::factory()->create([
        'name' => 'Istanbul Summer Escape',
        'destination_id' => $istanbul->id,
        'start_date' => '2026-06-10',
        'end_date' => '2026-06-20',
        'max_guests' => 4,
        'is_active' => true,
    ]);

    Package::factory()->create([
        'name' => 'Small Group Umrah',
        'destination_id' => $mecca->id,
        'start_date' => '2026-06-10',
        'end_date' => '2026-06-20',
        'max_guests' => 1,
        'is_active' => true,
    ]);

    $response = $this->get(route('packages.search', [
        'destination' => 'Mecca',
        'travel_date' => '2026-06-15',
        'guests' => 2,
    ]));

    $response->assertSuccessful();
    $response->assertSee($matchingPackage->name);
    $response->assertDontSee('Inactive Mecca Package');
    $response->assertDontSee('Istanbul Summer Escape');
    $response->assertDontSee('Small Group Umrah');
});

test('package search shows an empty state when no results match', function () {
    $egypt = Destination::factory()->create([
        'name' => ['en' => 'Egypt'],
        'slug' => 'egypt',
    ]);

    Package::factory()->create([
        'destination_id' => $egypt->id,
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-10',
        'max_guests' => 2,
    ]);

    $response = $this->get(route('packages.search', [
        'destination' => 'Mecca',
        'travel_date' => '2026-06-15',
        'guests' => 5,
    ]));

    $response->assertSuccessful();
    $response->assertSee('No packages matched your search');
});

test('packages page displays active packages only', function () {
    $activePackage = Package::factory()->create([
        'name' => 'Cairo Discovery',
        'is_active' => true,
    ]);

    Package::factory()->create([
        'name' => 'Inactive Hidden Package',
        'is_active' => false,
    ]);

    $response = $this->get(route('packages'));

    $response->assertSuccessful();
    $response->assertSee($activePackage->name);
    $response->assertDontSee('Inactive Hidden Package');
});

test('packages page renders arabic static copy', function () {
    $response = $this->get('/ar/packages');

    $response->assertSuccessful();
    $response->assertSee('باقاتنا');
    $response->assertSee('لا توجد باقات متاحة حالياً');
});

test('packages page renders destination filters and package destination attributes', function () {
    $destinations = collect(range(1, 8))->map(function (int $index) {
        return Destination::factory()->create([
            'name' => ['en' => "Destination {$index}"],
            'slug' => "destination-{$index}",
            'sort_order' => $index,
        ]);
    });

    $destinations->each(function (Destination $destination, int $index): void {
        Package::factory()->create([
            'destination_id' => $destination->id,
            'is_active' => true,
            'sort_order' => $index,
        ]);
    });

    $response = $this->get(route('packages'));

    $response->assertSuccessful();
    $response->assertSee('id="filter"', false);
    $response->assertSee('data-package-filter="destination-1"', false);
    $response->assertSee('data-package-filter="destination-7"', false);
    $response->assertDontSee('data-package-filter="destination-8"', false);
    $response->assertSee('data-destination="destination-1"', false);
});

test('packages page shows empty state when no active packages exist', function () {
    Package::factory()->create([
        'is_active' => false,
    ]);

    $response = $this->get(route('packages'));

    $response->assertSuccessful();
    $response->assertSee('No packages available right now');
});

test('package search renders arabic static copy', function () {
    $response = $this->get('/ar/packages/search');

    $response->assertSuccessful();
    $response->assertSee('بحث الباقات');
    $response->assertSee('اعثر على الباقة المناسبة لرحلتك');
    $response->assertSee('لا توجد باقات مطابقة لبحثك');
});
