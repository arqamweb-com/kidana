<?php

use App\Models\Package;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a service has many packages and package belongs to service', function () {
    $service = Service::factory()->create([
        'name' => 'Umrah Services',
    ]);

    $linkedPackage = Package::factory()->create([
        'service_id' => $service->id,
        'name' => 'Umrah Gold',
    ]);

    Package::factory()->create([
        'service_id' => null,
        'name' => 'Independent Package',
    ]);

    expect($service->packages)->toHaveCount(1)
        ->and($service->packages->first()->name)->toBe('Umrah Gold')
        ->and($linkedPackage->fresh()->service?->is($service))->toBeTrue();
});

test('package can store multiple tags and be queried by tag', function () {
    $taggedPackage = Package::factory()->create([
        'tags' => ['home', 'featured'],
    ]);

    Package::factory()->create([
        'tags' => ['umrah'],
    ]);

    expect($taggedPackage->fresh()->tags)->toBe(['home', 'featured'])
        ->and(Package::query()->tagged('featured')->pluck('id')->all())->toBe([$taggedPackage->id]);
});
