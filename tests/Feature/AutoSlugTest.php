<?php

use App\Models\Office;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Service;
use App\Models\Destination;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('slug is generated automatically when models are created without one', function () {
    $service = Service::create([
        'name' => ['en' => 'Airport Transfer'],
        'description' => ['en' => 'Private transfers.'],
        'is_active' => true,
    ]);

    $package = Package::create([
        'name' => ['en' => 'Premium Umrah'],
        'description' => ['en' => 'Premium package.'],
        'price' => 2500,
        'is_active' => true,
    ]);

    $destination = Destination::create([
        'name' => ['en' => 'Saudi Arabia'],
        'is_active' => true,
    ]);

    $office = Office::create([
        'name' => ['en' => 'Cairo Office'],
        'description' => ['en' => 'Main office.'],
        'is_active' => true,
    ]);

    $partner = Partner::create([
        'name' => ['en' => 'Sky Partner'],
        'is_active' => true,
    ]);

    expect($service->slug)->toBe('airport-transfer')
        ->and($package->slug)->toBe('premium-umrah')
        ->and($destination->slug)->toBe('saudi-arabia')
        ->and($office->slug)->toBe('cairo-office')
        ->and($partner->slug)->toBe('sky-partner');
});

test('auto generated slugs are unique per model table', function () {
    Office::create([
        'name' => ['en' => 'Cairo Office'],
        'description' => ['en' => 'Main office.'],
    ]);

    $office = Office::create([
        'name' => ['en' => 'Cairo Office'],
        'description' => ['en' => 'Second office.'],
    ]);

    expect($office->slug)->toBe('cairo-office-2');
});
