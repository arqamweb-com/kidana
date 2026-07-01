<?php

use App\Filament\Imports\PackageImporter;
use App\Models\Destination;
use App\Models\Package;
use App\Models\Service;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Run a single CSV row through the importer pipeline and persist it.
 */
function importPackageRow(array $row): Package
{
    $importer = new PackageImporter(
        new Import,
        array_combine(array_keys($row), array_keys($row)),
        [],
    );

    $importer->__invoke($row);

    return Package::query()->latest('id')->firstOrFail();
}

test('it creates a package from a csv row', function () {
    $service = Service::factory()->create();
    $destination = Destination::factory()->create();

    $package = importPackageRow([
        'name' => 'Cairo Explorer',
        'slug' => 'cairo-explorer',
        'service_id' => (string) $service->id,
        'destination_id' => (string) $destination->id,
        'description' => 'Five days in Cairo',
        'price' => '4500',
        'order_action' => 'fawry_payment',
        'is_active' => '1',
        'features' => 'Hotel | Flights | Guide',
        'tags' => 'egypt | culture',
    ]);

    expect($package->name)->toBe('Cairo Explorer')
        ->and($package->slug)->toBe('cairo-explorer')
        ->and($package->service_id)->toBe($service->id)
        ->and($package->destination_id)->toBe($destination->id)
        ->and($package->price)->toBe(4500)
        ->and($package->is_active)->toBeTrue()
        ->and($package->features)->toBe(['Hotel', 'Flights', 'Guide'])
        ->and($package->tags)->toBe(['egypt', 'culture']);
});

test('it updates an existing package matched by slug', function () {
    $existing = Package::factory()->create([
        'slug' => 'nile-cruise',
        'price' => 1000,
    ]);

    importPackageRow([
        'name' => 'Nile Cruise Deluxe',
        'slug' => 'nile-cruise',
        'price' => '7800',
    ]);

    expect(Package::where('slug', 'nile-cruise')->count())->toBe(1)
        ->and($existing->fresh()->price)->toBe(7800);
});
