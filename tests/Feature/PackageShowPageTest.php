<?php

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('active package show page is accessible by slug', function () {
    $package = Package::factory()->create([
        'name' => 'Grand Umrah Program',
        'slug' => 'grand-umrah-program',
        'description' => 'Premium 10-day journey with guided activities.',
        'features' => ['Visa support', 'Airport transfer'],
        'is_active' => true,
    ]);

    $response = $this->get(route('packages.show', ['package' => $package->slug]));

    $response->assertSuccessful();
    $response->assertSee('Grand Umrah Program');
    $response->assertSee('aria-label="Breadcrumb"', false);
    $response->assertSee(route('packages'), false);
    $response->assertSee('Premium 10-day journey with guided activities.');
    $response->assertSee('Visa support');
    $response->assertSee(route('packages.search'), false);
});

test('package show page renders arabic static copy', function () {
    $package = Package::factory()->create([
        'name' => ['ar' => 'باقة العمرة الفاخرة', 'en' => 'Luxury Umrah Package'],
        'slug' => 'luxury-umrah-package',
        'description' => ['ar' => 'رحلة عمرة مميزة.', 'en' => 'A premium Umrah journey.'],
        'is_active' => true,
    ]);

    $response = $this->get("/ar/packages/{$package->slug}");

    $response->assertSuccessful();
    $response->assertSee('احجز الآن');
    $response->assertSee('عن هذه الباقة');
    $response->assertSee('سافر بثقة');
});

test('inactive package show page returns not found', function () {
    $package = Package::factory()->create([
        'slug' => 'private-package',
        'is_active' => false,
    ]);

    $response = $this->get(route('packages.show', ['package' => $package->slug]));

    $response->assertNotFound();
});

test('package show page displays package gallery images', function () {
    $package = Package::factory()->create([
        'name' => 'Gallery Package',
        'slug' => 'gallery-package',
        'gallery' => [
            [
                'image' => 'packages/gallery/kaaba.jpg',
                'caption' => 'Kaaba view',
            ],
            [
                'image' => 'packages/gallery/hotel.jpg',
                'caption' => 'Hotel stay',
            ],
        ],
        'is_active' => true,
    ]);

    $response = $this->get(route('packages.show', ['package' => $package->slug]));

    $response->assertSuccessful();
    $response->assertSee('/storage/packages/gallery/kaaba.jpg', false);
    $response->assertSee('/storage/packages/gallery/hotel.jpg', false);
    $response->assertSee('data-package-gallery', false);
    $response->assertSee('data-package-gallery-featured', false);
    $response->assertSee('data-package-gallery-thumbnail', false);
    $response->assertSee('data-package-gallery-src', false);
    $response->assertSee('Kaaba view');
    $response->assertSee('Hotel stay');
});

test('package show page displays package itinerary items', function () {
    $package = Package::factory()->create([
        'name' => 'Itinerary Package',
        'slug' => 'itinerary-package',
        'itinerary' => [
            [
                'day_label' => 'Day 1',
                'icon' => 'heroicon-o-map',
                'title' => 'Arrival in Jeddah',
                'description' => 'Meet the guide and transfer to the hotel.',
            ],
            [
                'day_label' => 'Day 2',
                'icon' => 'heroicon-o-star',
                'title' => 'Umrah Rituals',
                'description' => 'Perform Umrah with guided support.',
            ],
        ],
        'is_active' => true,
    ]);

    $response = $this->get(route('packages.show', ['package' => $package->slug]));

    $response->assertSuccessful();
    $response->assertSee('data-package-accordion', false);
    $response->assertSee('data-package-accordion-trigger', false);
    $response->assertSee('data-package-accordion-panel', false);
    $response->assertSee('Day 1');
    $response->assertSee('Arrival in Jeddah');
    $response->assertSee('Meet the guide and transfer to the hotel.');
    $response->assertSee('Day 2');
    $response->assertSee('Umrah Rituals');
    $response->assertSee('Perform Umrah with guided support.');
});

test('package show page displays package included excluded and highlight items', function () {
    $package = Package::factory()->create([
        'name' => 'Details Package',
        'slug' => 'details-package',
        'included_items' => [
            [
                'icon' => 'heroicon-o-check',
                'title' => 'Visa processing',
            ],
            [
                'icon' => 'heroicon-o-bus',
                'title' => 'Private transfers',
            ],
        ],
        'excluded_items' => [
            [
                'icon' => 'heroicon-o-x-mark',
                'title' => 'International flights',
            ],
            [
                'icon' => 'heroicon-o-shopping-bag',
                'title' => 'Personal shopping',
            ],
        ],
        'highlights' => [
            [
                'icon' => 'heroicon-o-star',
                'title' => 'Prime hotel location',
            ],
            [
                'icon' => 'heroicon-o-sparkles',
                'title' => 'Personalized support',
            ],
        ],
        'is_active' => true,
    ]);

    $response = $this->get(route('packages.show', ['package' => $package->slug]));

    $response->assertSuccessful();
    $response->assertSee(__('packages.show.included_title'));
    $response->assertSee('Visa processing');
    $response->assertSee('Private transfers');
    $response->assertSee(__('packages.show.excluded_title'));
    $response->assertSee('International flights');
    $response->assertSee('Personal shopping');
    $response->assertSee(__('packages.show.highlights_eyebrow'));
    $response->assertSee('Prime hotel location');
    $response->assertSee('Personalized support');
});

test('package show page displays active package faqs in an accordion', function () {
    $package = Package::factory()->create([
        'name' => 'FAQ Package',
        'slug' => 'faq-package',
        'is_active' => true,
    ]);
    $activeFaq = Faq::factory()->create([
        'title' => 'Can I customize this package?',
        'answer' => 'Yes, our team can tailor the package details.',
        'is_active' => true,
        'sort_order' => 1,
    ]);
    $inactiveFaq = Faq::factory()->create([
        'title' => 'Hidden package question',
        'answer' => 'This should not be visible.',
        'is_active' => false,
        'sort_order' => 2,
    ]);

    $package->faqs()->attach([$activeFaq->id, $inactiveFaq->id]);

    $response = $this->get(route('packages.show', ['package' => $package->slug]));

    $response->assertSuccessful();
    $response->assertSee('data-package-accordion', false);
    $response->assertSee('data-package-accordion-trigger', false);
    $response->assertSee('data-package-accordion-panel', false);
    $response->assertSee('Can I customize this package?');
    $response->assertSee('Yes, our team can tailor the package details.');
    $response->assertDontSee('Hidden package question');
    $response->assertDontSee('This should not be visible.');
});

test('packages page links each package to package show page', function () {
    $package = Package::factory()->create([
        'slug' => 'riyadh-special',
    ]);

    $response = $this->get(route('packages'));

    $response->assertSuccessful();
    $response->assertSee(route('packages.show', ['package' => $package->slug]), false);
});

test('package search results link each package to package show page', function () {
    $destination = Destination::factory()->create([
        'name' => ['en' => 'Mecca'],
        'slug' => 'mecca',
    ]);

    $package = Package::factory()->create([
        'name' => 'Makkah Elite',
        'slug' => 'makkah-elite',
        'destination_id' => $destination->id,
        'is_active' => true,
    ]);

    $response = $this->get(route('packages.search', ['destination' => 'Mecca']));

    $response->assertSuccessful();
    $response->assertSee($package->name);
    $response->assertSee(route('packages.show', ['package' => $package->slug]), false);
});
