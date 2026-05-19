<?php

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Office;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('content resources persist translated fields', function () {
    $destination = Destination::create([
        'name' => [
            'en' => 'Mecca',
            'ar' => 'مكة',
        ],
        'slug' => 'mecca',
        'is_active' => true,
    ]);

    $package = Package::create([
        'name' => [
            'en' => 'Premium Umrah',
            'ar' => 'عمرة مميزة',
        ],
        'slug' => 'premium-umrah',
        'description' => [
            'en' => 'A curated spiritual journey.',
            'ar' => 'رحلة روحانية منظمة.',
        ],
        'destination_id' => $destination->id,
        'price' => 2500,
        'is_active' => true,
    ]);

    $office = Office::create([
        'name' => [
            'en' => 'Cairo Office',
            'ar' => 'مكتب القاهرة',
        ],
        'slug' => 'cairo-office',
        'description' => [
            'en' => 'Main sales office.',
            'ar' => 'مكتب المبيعات الرئيسي.',
        ],
        'is_active' => true,
    ]);

    $partner = Partner::create([
        'name' => [
            'en' => 'Sky Partner',
            'ar' => 'شريك سكاي',
        ],
        'slug' => 'sky-partner',
        'is_active' => true,
    ]);

    $faq = Faq::create([
        'title' => [
            'en' => 'Is visa support included?',
            'ar' => 'هل دعم التأشيرة متاح؟',
        ],
        'answer' => [
            'en' => 'Yes, visa support is included.',
            'ar' => 'نعم، دعم التأشيرة متاح.',
        ],
        'tags' => ['home', 'service'],
        'is_active' => true,
    ]);

    $testimonial = Testimonial::create([
        'name' => [
            'en' => 'Ahmed Hassan',
            'ar' => 'أحمد حسن',
        ],
        'testimonial' => [
            'en' => 'The trip was well organized.',
            'ar' => 'كانت الرحلة منظمة جيدًا.',
        ],
        'position' => [
            'en' => 'Traveler',
            'ar' => 'مسافر',
        ],
        'tags' => ['home', 'package'],
        'is_active' => true,
    ]);

    $package->faqs()->attach($faq);
    $package->testimonials()->attach($testimonial);

    expect($destination->fresh()->getTranslation('name', 'ar'))->toBe('مكة')
        ->and($package->fresh()->getTranslation('name', 'ar'))->toBe('عمرة مميزة')
        ->and($package->fresh()->getTranslation('description', 'en'))->toBe('A curated spiritual journey.')
        ->and($office->fresh()->getTranslation('name', 'ar'))->toBe('مكتب القاهرة')
        ->and($office->fresh()->getTranslation('description', 'en'))->toBe('Main sales office.')
        ->and($partner->fresh()->getTranslation('name', 'ar'))->toBe('شريك سكاي')
        ->and($faq->fresh()->getTranslation('title', 'ar'))->toBe('هل دعم التأشيرة متاح؟')
        ->and($faq->fresh()->getTranslation('answer', 'en'))->toBe('Yes, visa support is included.')
        ->and($faq->fresh()->tags)->toBe(['home', 'service'])
        ->and($testimonial->fresh()->getTranslation('name', 'ar'))->toBe('أحمد حسن')
        ->and($testimonial->fresh()->getTranslation('testimonial', 'en'))->toBe('The trip was well organized.')
        ->and($testimonial->fresh()->getTranslation('position', 'ar'))->toBe('مسافر')
        ->and($testimonial->fresh()->tags)->toBe(['home', 'package'])
        ->and($package->fresh()->faqs)->toHaveCount(1)
        ->and($package->fresh()->testimonials)->toHaveCount(1);
});

test('faq and testimonial home tags can be queried', function () {
    Faq::factory()->create(['tags' => ['home']]);
    Faq::factory()->create(['tags' => ['package']]);
    Testimonial::factory()->create(['tags' => ['home']]);
    Testimonial::factory()->create(['tags' => ['service']]);

    expect(Faq::query()->tagged('home')->count())->toBe(1)
        ->and(Testimonial::query()->tagged('home')->count())->toBe(1);
});
