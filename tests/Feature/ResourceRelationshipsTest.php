<?php

use App\Models\Destination;
use App\Models\Faq;
use App\Models\Package;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('content resources keep their direct and inverse relationships in sync', function () {
    $service = Service::factory()->create();
    $destination = Destination::factory()->create();
    $package = Package::factory()->create([
        'service_id' => $service->id,
        'destination_id' => $destination->id,
    ]);
    $faq = Faq::factory()->create();
    $testimonial = Testimonial::factory()->create();

    $service->faqs()->attach($faq);
    $service->testimonials()->attach($testimonial);
    $package->faqs()->attach($faq);
    $package->testimonials()->attach($testimonial);

    expect($service->fresh()->packages)->toHaveCount(1)
        ->and($service->packages->first()->is($package))->toBeTrue()
        ->and($package->fresh()->service?->is($service))->toBeTrue()
        ->and($destination->fresh()->packages)->toHaveCount(1)
        ->and($destination->packages->first()->is($package))->toBeTrue()
        ->and($package->fresh()->destination?->is($destination))->toBeTrue()
        ->and($service->fresh()->faqs)->toHaveCount(1)
        ->and($service->faqs->first()->is($faq))->toBeTrue()
        ->and($faq->fresh()->services)->toHaveCount(1)
        ->and($faq->services->first()->is($service))->toBeTrue()
        ->and($package->fresh()->faqs)->toHaveCount(1)
        ->and($package->faqs->first()->is($faq))->toBeTrue()
        ->and($faq->fresh()->packages)->toHaveCount(1)
        ->and($faq->packages->first()->is($package))->toBeTrue()
        ->and($service->fresh()->testimonials)->toHaveCount(1)
        ->and($service->testimonials->first()->is($testimonial))->toBeTrue()
        ->and($testimonial->fresh()->services)->toHaveCount(1)
        ->and($testimonial->services->first()->is($service))->toBeTrue()
        ->and($package->fresh()->testimonials)->toHaveCount(1)
        ->and($package->testimonials->first()->is($testimonial))->toBeTrue()
        ->and($testimonial->fresh()->packages)->toHaveCount(1)
        ->and($testimonial->packages->first()->is($package))->toBeTrue();
});

test('deleting linked content cleans up resource pivot records', function () {
    $service = Service::factory()->create();
    $package = Package::factory()->create([
        'service_id' => $service->id,
    ]);
    $faq = Faq::factory()->create();
    $testimonial = Testimonial::factory()->create();

    $service->faqs()->attach($faq);
    $service->testimonials()->attach($testimonial);
    $package->faqs()->attach($faq);
    $package->testimonials()->attach($testimonial);

    $faq->delete();
    $testimonial->delete();

    expect($service->fresh()->faqs)->toHaveCount(0)
        ->and($service->fresh()->testimonials)->toHaveCount(0)
        ->and($package->fresh()->faqs)->toHaveCount(0)
        ->and($package->fresh()->testimonials)->toHaveCount(0);
});
