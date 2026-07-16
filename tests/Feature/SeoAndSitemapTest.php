<?php

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('package pages render core seo tags', function () {
    $package = Package::factory()->create([
        'name' => 'SEO Umrah Package',
        'slug' => 'seo-umrah-package',
        'description' => 'A search friendly package description for premium Umrah travelers.',
        'image_url' => 'https://example.com/package.jpg',
        'is_active' => true,
    ]);

    $response = $this->get(route('packages.show', ['package' => $package->slug]));

    $response->assertSuccessful();
    $response->assertSee('content="A search friendly package description for premium Umrah travelers."', false);
    $response->assertSee('<link rel="canonical" href="'.route('packages.show', ['package' => $package->slug]).'">', false);
    $response->assertSee('<meta property="og:image" content="https://example.com/package.jpg">', false);
});

test('home page renders localized title and meta description', function (string $locale, string $title, string $description) {
    $response = $this->get(route('home', ['locale' => $locale]));

    $response->assertSuccessful();
    $response->assertSee('<title>'.e($title).'</title>', false);
    $response->assertSee('content="'.e($description).'"', false);
})->with([
    ['en', 'Kidana Travel – Luxury Hajj, Umrah & Travel', 'Premium luxury travel experiences for Hajj, Umrah, and international destinations. 5-star service, VIP transfers, and exclusive packages.'],
    ['ar', 'كدانة للسفر – الحج والعمرة والسفر الفاخر', 'تجارب سفر فاخرة ومميزة للحج والعمرة والوجهات الدولية. خدمة 5 نجوم، وتنقلات كبار الشخصيات، وباقات حصرية.'],
    ['fr', 'Kidana Travel – Hajj, Omra et voyages de luxe', 'Des expériences de voyage haut de gamme pour le Hajj, la Omra et les destinations internationales. Service 5 étoiles, transferts VIP et forfaits exclusifs.'],
    ['id', 'Kidana Travel – Haji, Umrah & Perjalanan Mewah', 'Pengalaman perjalanan mewah premium untuk Haji, Umrah, dan destinasi internasional. Layanan bintang 5, transfer VIP, dan paket eksklusif.'],
]);

test('sitemap exposes localized public urls and active packages', function () {
    $package = Package::factory()->create([
        'slug' => 'sitemap-package',
        'is_active' => true,
    ]);

    $response = $this->get(route('sitemap'));

    $response->assertSuccessful();
    $response->assertHeader('Content-Type', 'application/xml');
    $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
    $response->assertSee(route('home', ['locale' => 'en']), false);
    $response->assertSee(route('packages.show', ['locale' => 'en', 'package' => $package->slug]), false);
});
