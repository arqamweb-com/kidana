<?php

use App\Models\Destination;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a destination has many packages and a package belongs to a destination', function () {
    $destination = Destination::factory()->create([
        'name' => ['en' => 'Mecca'],
        'slug' => 'mecca',
    ]);

    $linkedPackage = Package::factory()->create([
        'destination_id' => $destination->id,
        'name' => 'Umrah Gold',
    ]);

    Package::factory()->create([
        'name' => 'Independent Package',
    ]);

    expect($destination->packages)->toHaveCount(1)
        ->and($destination->packages->first()->name)->toBe('Umrah Gold')
        ->and($linkedPackage->fresh()->destination?->is($destination))->toBeTrue();
});

test('destinations index displays active destinations only', function () {
    $activeDestination = Destination::factory()->create([
        'name' => 'Makkah',
        'slug' => 'makkah',
        'is_active' => true,
    ]);

    Destination::factory()->create([
        'name' => 'Hidden Destination',
        'slug' => 'hidden-destination',
        'is_active' => false,
    ]);

    $response = $this->get(route('destinations.index'));

    $response->assertSuccessful();
    $response->assertSee('Makkah');
    $response->assertSee(route('destinations.show', ['destination' => $activeDestination->slug]), false);
    $response->assertDontSee('Hidden Destination');
});

test('home view all destinations button links to destinations index', function () {
    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee(route('destinations.index'), false);
    $response->assertSee('All Destinations');
});

test('destination show displays active packages only', function () {
    $destination = Destination::factory()->create([
        'name' => ['en' => 'Egypt'],
        'slug' => 'egypt',
        'is_active' => true,
    ]);

    $activePackage = Package::factory()->create([
        'destination_id' => $destination->id,
        'name' => 'Cairo Discovery',
        'slug' => 'cairo-discovery',
        'is_active' => true,
    ]);

    Package::factory()->create([
        'destination_id' => $destination->id,
        'name' => 'Hidden Egypt Package',
        'is_active' => false,
    ]);

    $response = $this->get(route('destinations.show', ['destination' => $destination->slug]));

    $response->assertSuccessful();
    $response->assertSee('Egypt');
    $response->assertSee($activePackage->name);
    $response->assertSee(route('packages.show', ['package' => $activePackage->slug]), false);
    $response->assertDontSee('Hidden Egypt Package');
});

test('inactive destination show page returns not found', function () {
    $destination = Destination::factory()->create([
        'slug' => 'hidden-destination',
        'is_active' => false,
    ]);

    $response = $this->get(route('destinations.show', ['destination' => $destination->slug]));

    $response->assertNotFound();
});
