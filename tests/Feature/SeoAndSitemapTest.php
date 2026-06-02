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
