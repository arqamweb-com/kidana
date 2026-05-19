<?php

use App\Models\Destination;
use App\Models\Package;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('services index displays active services only', function () {
    $activeService = Service::factory()->create([
        'name' => 'Airport Transfer',
        'slug' => 'airport-transfer',
        'is_active' => true,
    ]);

    Service::factory()->create([
        'name' => 'Hidden Service',
        'slug' => 'hidden-service',
        'is_active' => false,
    ]);

    $response = $this->get(route('services.index'));

    $response->assertSuccessful();
    $response->assertSee('Airport Transfer');
    $response->assertDontSee('Hidden Service');
    $response->assertSee(route('services.show', ['service' => $activeService->slug]), false);
});

test('services index renders arabic static copy', function () {
    $response = $this->get('/ar/services');

    $response->assertSuccessful();
    $response->assertSee('خدماتنا');
    $response->assertSee('لا توجد خدمات متاحة حالياً');
});

test('navbar services dropdown lists active services only', function () {
    $activeService = Service::factory()->create([
        'name' => 'Airport Transfer',
        'slug' => 'airport-transfer',
        'is_active' => true,
    ]);

    Service::factory()->create([
        'name' => 'Hidden Service',
        'slug' => 'hidden-service',
        'is_active' => false,
    ]);

    $response = $this->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('Airport Transfer');
    $response->assertSee(route('services.show', ['service' => $activeService->slug]), false);
    $response->assertDontSee('Hidden Service');
});

test('active service show page is accessible by slug', function () {
    $service = Service::factory()->create([
        'name' => 'VIP Meet & Assist',
        'slug' => 'vip-meet-assist',
        'is_active' => true,
        'hero_title' => 'VIP Meet & Assist',
        'hero_description' => 'Priority airport experience from arrival to transfer.',
        'benefits' => [
            ['icon' => 'heroicon-o-check-circle', 'title' => 'Fast Track', 'desc' => 'Skip long queues.'],
        ],
        'gallery' => [
            ['image' => 'https://example.com/service-gallery.jpg', 'caption' => 'VIP arrival lounge'],
        ],
    ]);

    $serviceTestimonial = Testimonial::factory()->create([
        'name' => ['en' => 'Service Client'],
        'testimonial' => ['en' => 'The service team was excellent.'],
        'position' => ['en' => 'Cairo, Egypt'],
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $unrelatedTestimonial = Testimonial::factory()->create([
        'name' => ['en' => 'Unrelated Client'],
        'testimonial' => ['en' => 'This should not appear on this service.'],
        'is_active' => true,
        'sort_order' => 2,
    ]);

    $service->testimonials()->attach($serviceTestimonial);

    $response = $this->get(route('services.show', ['service' => $service->slug]));

    $response->assertSuccessful();
    $response->assertSee('VIP Meet & Assist');
    $response->assertSee('Priority airport experience from arrival to transfer.');
    $response->assertSee('aria-label="Breadcrumb"', false);
    $response->assertSee(route('services.index'), false);
    $response->assertSee('Key Benefits');
    $response->assertSee('Fast Track');
    $response->assertSee('Skip long queues.');
    $response->assertSee('https://example.com/service-gallery.jpg', false);
    $response->assertSee('VIP arrival lounge');
    $response->assertSee('Service Client');
    $response->assertSee('The service team was excellent.');
    $response->assertDontSee('Unrelated Client');
    $response->assertDontSee('This should not appear on this service.');
});

test('service show page renders arabic static copy', function () {
    $service = Service::factory()->create([
        'name' => ['ar' => 'خدمة كبار الشخصيات', 'en' => 'VIP Service'],
        'slug' => 'vip-service',
        'is_active' => true,
    ]);

    $response = $this->get("/ar/services/{$service->slug}");

    $response->assertSuccessful();
    $response->assertSee('اطلب باقتك');
    $response->assertSee('المزايا الرئيسية');
    $response->assertSee('اطلب هذه الخدمة');
});

test('service show displays only active packages linked to the current service', function () {
    $destination = Destination::factory()->create([
        'name' => ['en' => 'Cairo'],
        'slug' => 'cairo',
    ]);

    $service = Service::factory()->create([
        'name' => 'Desert Adventures',
        'slug' => 'desert-adventures',
        'is_active' => true,
    ]);

    $otherService = Service::factory()->create([
        'name' => 'City Tours',
        'slug' => 'city-tours',
        'is_active' => true,
    ]);

    $linkedPackage = Package::factory()->create([
        'service_id' => $service->id,
        'name' => 'Safari Gold',
        'slug' => 'safari-gold',
        'destination_id' => $destination->id,
        'is_active' => true,
    ]);

    Package::factory()->create([
        'service_id' => $service->id,
        'name' => 'Hidden Inactive Package',
        'slug' => 'hidden-inactive-package',
        'is_active' => false,
    ]);

    Package::factory()->create([
        'service_id' => $otherService->id,
        'name' => 'City Deluxe',
        'slug' => 'city-deluxe',
        'is_active' => true,
    ]);

    $response = $this->get(route('services.show', ['service' => $service->slug]));

    $response->assertSuccessful();
    $response->assertSee('Safari Gold');
    $response->assertSee(route('packages.show', ['package' => $linkedPackage->slug]), false);
    $response->assertDontSee('Hidden Inactive Package');
    $response->assertDontSee('City Deluxe');
});

test('inactive service show page returns not found', function () {
    $service = Service::factory()->create([
        'slug' => 'internal-service',
        'is_active' => false,
    ]);

    $response = $this->get(route('services.show', ['service' => $service->slug]));

    $response->assertNotFound();
});

test('missing service slug returns not found', function () {
    $response = $this->get('/services/missing-service');

    $response->assertNotFound();
});
