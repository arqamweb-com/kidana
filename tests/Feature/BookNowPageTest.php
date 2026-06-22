<?php

use App\Models\Destination;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('book now page loads and includes the working package search form', function () {
    $destination = Destination::factory()->create([
        'name' => ['en' => 'Egypt'],
        'slug' => 'egypt',
    ]);

    $response = $this->get(route('book-now'));

    $response->assertSuccessful();
    $response->assertSee(route('packages.search'), false);
    $response->assertSee('name="destination"', false);
    $response->assertSee('name="travel_date"', false);
    $response->assertSee('name="guests"', false);
    $response->assertSee($destination->slug);
});

test('book now page uses localized copy for supported languages', function (string $locale, string $heading, string $customTitle) {
    $response = $this->get(route('book-now', ['locale' => $locale]));

    $response->assertSuccessful();
    $response->assertSee($heading);
    $response->assertSee($customTitle);
})->with([
    'arabic' => ['ar', 'ابدأ رحلتك مع', 'اصنع باقتك المخصصة'],
    'french' => ['fr', 'Commencez votre voyage avec', 'Créer votre forfait sur mesure'],
    'indonesian' => ['id', 'Mulai Perjalanan Anda bersama', 'Buat Paket Khusus Anda'],
]);

test('book now tabs render active packages, services and destinations dynamically', function () {
    $destination = Destination::factory()->create([
        'name' => ['en' => 'Cairo'],
        'slug' => 'cairo',
    ]);

    $service = Service::factory()->create([
        'name' => ['en' => 'Guided Tours'],
        'slug' => 'guided-tours',
    ]);

    $package = Package::factory()->create([
        'destination_id' => $destination->id,
        'service_id' => $service->id,
        'name' => 'Cairo Pyramids Discovery',
        'slug' => 'cairo-pyramids-discovery',
        'is_active' => true,
    ]);

    Package::factory()->create([
        'name' => 'Hidden Package',
        'is_active' => false,
    ]);

    $response = $this->get(route('book-now'));

    $response->assertSuccessful();
    $response->assertSee('data-booknow-tabs', false);
    $response->assertSee($package->name);
    $response->assertSee(route('packages.show', ['package' => $package->slug]), false);
    $response->assertSee('Guided Tours');
    $response->assertSee(route('services.show', ['service' => $service->slug]), false);
    $response->assertSee('Cairo');
    $response->assertSee(route('destinations.show', ['destination' => $destination->slug]), false);
    $response->assertDontSee('Hidden Package');
});
