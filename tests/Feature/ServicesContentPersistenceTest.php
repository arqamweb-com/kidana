<?php

use App\Models\Faq;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('service page content fields are persisted including benefits', function () {
    $payload = [
        'name' => [
            'en' => 'Desert Safari',
            'es' => 'Safari por el desierto',
        ],
        'slug' => 'desert-safari',
        'description' => [
            'en' => 'Adventure across the dunes.',
            'es' => 'Aventura por las dunas.',
        ],
        'hero_title' => [
            'en' => 'Explore the desert',
            'es' => 'Explora el desierto',
        ],
        'hero_subtitle' => [
            'en' => 'Private tours and sunset camps.',
            'es' => 'Tours privados y campamentos al atardecer.',
        ],
        'hero_image' => 'https://example.com/hero.jpg',
        'intro_title' => [
            'en' => 'Why choose this service?',
            'es' => 'Por que elegir este servicio?',
        ],
        'intro_text' => [
            'en' => 'Safe, guided, and customized trips.',
            'es' => 'Viajes seguros, guiados y personalizados.',
        ],
        'stats' => [
            ['label' => 'Trips', 'value' => '120+'],
        ],
        'benefits' => [
            ['icon' => 'heroicon-o-check-circle', 'title' => 'Expert guides', 'desc' => 'Local, experienced team.'],
            ['icon' => 'heroicon-o-clock', 'title' => 'Flexible schedule', 'desc' => 'Morning or evening departures.'],
        ],
        'steps' => [
            ['title' => 'Book', 'desc' => 'Choose your preferred date.'],
            ['title' => 'Confirm', 'desc' => 'Receive confirmation and pickup details.'],
        ],
        'gallery' => [
            ['image' => 'https://example.com/gallery-1.jpg', 'caption' => 'Sunset dunes'],
        ],
        'cta_title' => [
            'en' => 'Book your trip now',
            'es' => 'Reserva tu viaje ahora',
        ],
        'cta_subtitle' => [
            'en' => 'Limited slots available this week.',
            'es' => 'Plazas limitadas esta semana.',
        ],
        'is_active' => true,
        'sort_order' => 1,
    ];

    $service = Service::create($payload);
    $faq = Faq::factory()->create();
    $testimonial = Testimonial::factory()->create();

    $service->faqs()->attach($faq);
    $service->testimonials()->attach($testimonial);

    $this->assertDatabaseHas('services', [
        'id' => $service->id,
        'slug' => 'desert-safari',
    ]);

    $refreshedService = $service->fresh();

    expect($refreshedService->benefits)->toEqual($payload['benefits'])
        ->and($refreshedService->stats)->toEqual($payload['stats'])
        ->and($refreshedService->steps)->toEqual($payload['steps'])
        ->and($refreshedService->faqs)->toHaveCount(1)
        ->and($refreshedService->testimonials)->toHaveCount(1)
        ->and($refreshedService->faqs->first()->is($faq))->toBeTrue()
        ->and($refreshedService->testimonials->first()->is($testimonial))->toBeTrue()
        ->and($refreshedService->getTranslation('name', 'en'))->toBe('Desert Safari')
        ->and($refreshedService->getTranslation('name', 'es'))->toBe('Safari por el desierto');
});
