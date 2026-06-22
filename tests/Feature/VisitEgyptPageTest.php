<?php

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('visit egypt page loads successfully', function () {
    $response = $this->get(route('visit-egypt'));

    $response->assertSuccessful();
    $response->assertSee('Explore Egypt Packages');
    $response->assertSee('Custom Egypt Package');
});

test('visit egypt page uses localized copy for supported languages', function (string $locale, string $heading, string $customPackage) {
    $response = $this->get(route('visit-egypt', ['locale' => $locale]));

    $response->assertSuccessful();
    $response->assertSee($heading);
    $response->assertSee($customPackage);
})->with([
    'arabic' => ['ar', 'استكشف باقات مصر', 'باقة مصر مخصصة'],
    'french' => ['fr', 'Explorer les forfaits Égypte', 'Forfait Égypte sur mesure'],
    'indonesian' => ['id', 'Jelajahi Paket Mesir', 'Paket Mesir Khusus'],
]);

test('visit egypt packages section shows active packages of the egypt destination only', function () {
    $egypt = Destination::factory()->create([
        'name' => ['en' => 'Egypt'],
        'slug' => 'egypt',
        'is_active' => true,
    ]);

    $other = Destination::factory()->create([
        'name' => ['en' => 'Maldives'],
        'slug' => 'maldives',
        'is_active' => true,
    ]);

    $egyptPackage = Package::factory()->create([
        'destination_id' => $egypt->id,
        'name' => 'Cairo Discovery',
        'slug' => 'cairo-discovery',
        'is_active' => true,
    ]);

    Package::factory()->create([
        'destination_id' => $egypt->id,
        'name' => 'Hidden Egypt Package',
        'is_active' => false,
    ]);

    Package::factory()->create([
        'destination_id' => $other->id,
        'name' => 'Maldives Escape',
        'is_active' => true,
    ]);

    $response = $this->get(route('visit-egypt'));

    $response->assertSuccessful();
    $response->assertSee($egyptPackage->name);
    $response->assertSee(route('packages.show', ['package' => $egyptPackage->slug]), false);
    $response->assertDontSee('Hidden Egypt Package');
    $response->assertDontSee('Maldives Escape');
});

test('visit egypt faq section shows active faqs tagged egypt only', function () {
    $egyptFaq = Faq::factory()->create([
        'title' => ['en' => 'Do I need a visa for Egypt?'],
        'answer' => ['en' => 'Most nationalities can obtain a visa on arrival.'],
        'tags' => ['egypt'],
        'is_active' => true,
    ]);

    Faq::factory()->create([
        'title' => ['en' => 'Hidden Egypt FAQ'],
        'tags' => ['egypt'],
        'is_active' => false,
    ]);

    Faq::factory()->create([
        'title' => ['en' => 'Unrelated Home FAQ'],
        'tags' => ['home'],
        'is_active' => true,
    ]);

    $response = $this->get(route('visit-egypt'));

    $response->assertSuccessful();
    $response->assertSee('Do I need a visa for Egypt?');
    $response->assertDontSee('Hidden Egypt FAQ');
    $response->assertDontSee('Unrelated Home FAQ');
});
