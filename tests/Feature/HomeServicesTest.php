<?php

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Office;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('home page displays active services from the dashboard', function () {
    $activeService = Service::factory()->create([
        'name' => 'Safari',
        'description' => 'Private desert adventures.',
        'image_url' => 'services/safari.jpg',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Service::factory()->create([
        'name' => 'Hidden Service',
        'description' => 'This should not be shown.',
        'is_active' => false,
        'sort_order' => 2,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee($activeService->name);
    $response->assertSee($activeService->description);
    $response->assertSee('/storage/services/safari.jpg', false);
    $response->assertDontSee('Hidden Service');
});

test('home page displays active destinations from the dashboard', function () {
    $activeDestination = Destination::factory()->create([
        'name' => 'Makkah',
        'slug' => 'makkah',
        'image_url' => 'destinations/makkah.jpg',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Destination::factory()->create([
        'name' => 'Hidden Destination',
        'slug' => 'hidden-destination',
        'is_active' => false,
        'sort_order' => 2,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('Makkah');
    $response->assertSee('/storage/destinations/makkah.jpg', false);
    $response->assertSee(route('destinations.show', ['destination' => $activeDestination->slug]), false);
    $response->assertDontSee('Hidden Destination');
});

test('home page ignores legacy cached destination strings on repeated requests', function () {
    Cache::put('home.page.en', [
        'destinations' => collect(['makkah']),
        'services' => collect(),
        'packages' => collect(),
        'homeFaqs' => collect(),
        'homeTestimonials' => collect(),
        'offices' => collect(),
        'partners' => collect(),
    ]);

    $activeDestination = Destination::factory()->create([
        'name' => 'Makkah',
        'slug' => 'makkah',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee(route('destinations.show', ['destination' => $activeDestination->slug]), false);

    $this->get(route('home'))
        ->assertSuccessful()
        ->assertSee(route('destinations.show', ['destination' => $activeDestination->slug]), false);
});

test('home page shows an empty services state when no active services exist', function () {
    Service::factory()->create([
        'is_active' => false,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('No services available yet');
});

test('home page displays six featured packages from the dashboard', function () {
    $packages = Package::factory()
        ->count(7)
        ->sequence(fn (Sequence $sequence): array => [
            'name' => ['en' => "Featured Package {$sequence->index}"],
            'slug' => "featured-package-{$sequence->index}",
            'sort_order' => $sequence->index,
            'is_active' => true,
        ])
        ->create();

    $hiddenPackage = Package::factory()->create([
        'name' => ['en' => 'Inactive Featured Package'],
        'slug' => 'inactive-featured-package',
        'sort_order' => 0,
        'is_active' => false,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    expect(substr_count($response->getContent(), 'data-package-card'))->toBe(6);

    $packages->take(6)->each(function (Package $package) use ($response): void {
        $response->assertSee($package->name);
        $response->assertSee(route('packages.show', ['package' => $package->slug]), false);
    });

    $response->assertDontSee($packages->last()->name);
    $response->assertDontSee($hiddenPackage->name);
});

test('home page displays active offices in the presence section', function () {
    Office::create([
        'name' => ['en' => 'Cairo Office'],
        'description' => ['en' => 'Main sales office.'],
        'location' => ['en' => 'Giza'],
        'image_url' => 'offices/cairo.jpg',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Office::create([
        'name' => ['en' => 'Hidden Office'],
        'description' => ['en' => 'This should not be shown.'],
        'location' => ['en' => 'Hidden City'],
        'is_active' => false,
        'sort_order' => 2,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('Cairo Office');
    $response->assertSee('Main sales office.');
    $response->assertSee('Giza');
    $response->assertSee('/storage/offices/cairo.jpg', false);
    $response->assertDontSee('Hidden Office');
});

test('home page displays active partners in the trusted partners section', function () {
    Partner::create([
        'name' => ['en' => 'Sky Partner'],
        'image_url' => 'partners/sky.png',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    Partner::create([
        'name' => ['en' => 'Hidden Partner'],
        'image_url' => 'partners/hidden.png',
        'is_active' => false,
        'sort_order' => 2,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('Sky Partner');
    $response->assertSee('/storage/partners/sky.png', false);
    $response->assertDontSee('Hidden Partner');
    $response->assertDontSee('/storage/partners/hidden.png', false);
});

test('faq section renders faq items', function () {
    $faqs = collect([
        Faq::factory()->make([
            'title' => ['en' => 'What is included in the package?'],
            'answer' => ['en' => 'All meals and accommodation are included.'],
        ]),
    ]);

    $response = $this->blade(
        '@include("sections.faq")',
        ['faqs' => $faqs],
    );

    $response->assertSee('What is included in the package?');
    $response->assertSee('All meals and accommodation are included.');
});
