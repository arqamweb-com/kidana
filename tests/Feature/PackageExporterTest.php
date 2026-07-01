<?php

use App\Filament\Imports\PackageImporter;
use App\Models\Destination;
use App\Models\Package;
use App\Services\PackageCsvExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Invoke a protected static helper on a class for testing.
 */
function callProtectedStatic(string $class, string $method, mixed ...$args): mixed
{
    $reflection = new ReflectionMethod($class, $method);
    $reflection->setAccessible(true);

    return $reflection->invoke(null, ...$args);
}

test('the exporter headers cover every importable column', function () {
    $exportHeaders = collect(PackageCsvExporter::headers())->sort()->values();
    $importNames = collect(PackageImporter::getColumns())
        ->map(fn ($column) => $column->getName())
        ->sort()
        ->values();

    expect($exportHeaders->all())->toBe($importNames->all());
});

test('a package is mapped to a csv row with pipe-joined array columns', function () {
    $package = Package::factory()->create([
        'name' => 'Cairo Explorer',
        'slug' => 'cairo-explorer',
        'is_active' => true,
        'features' => ['Hotel', 'Flights', 'Guide'],
    ]);

    $row = array_combine(PackageCsvExporter::headers(), PackageCsvExporter::row($package));

    expect($row['name'])->toBe('Cairo Explorer')
        ->and($row['slug'])->toBe('cairo-explorer')
        ->and($row['is_active'])->toBe('1')
        ->and($row['features'])->toBe('Hotel | Flights | Guide');
});

test('array columns round-trip between the exporter and importer', function () {
    $original = ['Hotel', 'Flights', 'Private Guide'];

    $package = Package::factory()->create(['features' => $original]);

    $exported = array_combine(PackageCsvExporter::headers(), PackageCsvExporter::row($package))['features'];
    $reimported = callProtectedStatic(PackageImporter::class, 'toList', $exported);

    expect($exported)->toBe('Hotel | Flights | Private Guide')
        ->and($reimported)->toBe($original);
});

test('it exports repeater-backed columns without failing on nested arrays', function () {
    $package = Package::factory()->create([
        'name' => 'Structured Package',
        'slug' => 'structured-package',
        'highlights' => [
            ['icon' => 'heroicon-o-star', 'title' => 'Pyramids'],
            ['icon' => 'heroicon-o-star', 'title' => 'Nile Cruise'],
        ],
        'itinerary' => [
            ['day_label' => 'Day 1', 'icon' => 'heroicon-o-map-pin', 'title' => 'Pickup', 'description' => ''],
        ],
    ]);

    $row = array_combine(PackageCsvExporter::headers(), PackageCsvExporter::row($package));

    expect($row['highlights'])->toBe('Pyramids | Nile Cruise')
        ->and($row['itinerary'])->toBe('Pickup');
});

test('the download response streams csv with a bom and header row', function () {
    Package::factory()->create([
        'name' => 'Nile Cruise',
        'slug' => 'nile-cruise',
        'destination_id' => Destination::factory(),
    ]);

    $response = app(PackageCsvExporter::class)->download(
        Package::query(),
        'packages.csv',
    );

    ob_start();
    $response->sendContent();
    $content = ob_get_clean();

    expect($response->headers->get('content-type'))->toContain('text/csv')
        ->and($content)->toStartWith("\xEF\xBB\xBF")
        ->and($content)->toContain('name,slug,service_id')
        ->and($content)->toContain('nile-cruise');
});
