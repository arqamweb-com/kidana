<?php

use App\Enum\Role;
use App\Filament\Resources\Destination\DestinationResource;
use App\Filament\Resources\Faq\FaqResource;
use App\Filament\Resources\Office\OfficeResource;
use App\Filament\Resources\Package\PackageResource;
use App\Filament\Resources\Partner\PartnerResource;
use App\Filament\Resources\Service\ServiceResource;
use App\Filament\Resources\Testimonial\TestimonialResource;
use App\Filament\Resources\User\UserResource;
use App\Models\Package;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can access all content resources', function () {
    $admin = User::factory()->make([
        'role' => Role::Admin,
    ]);

    $this->actingAs($admin);

    expect($admin->canAccessPanel(Filament::getPanel('admin')))->toBeTrue()
        ->and(UserResource::canAccess())->toBeTrue()
        ->and(UserResource::shouldRegisterNavigation())->toBeTrue()
        ->and(PackageResource::canAccess())->toBeTrue()
        ->and(DestinationResource::canAccess())->toBeTrue()
        ->and(ServiceResource::canAccess())->toBeTrue()
        ->and(OfficeResource::canAccess())->toBeTrue()
        ->and(PartnerResource::canAccess())->toBeTrue()
        ->and(FaqResource::canAccess())->toBeTrue()
        ->and(TestimonialResource::canAccess())->toBeTrue();
});

test('editor can access content resources but not users resource', function () {
    $editor = User::factory()->make([
        'role' => Role::Editor,
    ]);

    $this->actingAs($editor);

    expect($editor->canAccessPanel(Filament::getPanel('admin')))->toBeTrue()
        ->and(UserResource::canAccess())->toBeFalse()
        ->and(UserResource::shouldRegisterNavigation())->toBeFalse()
        ->and(PackageResource::canAccess())->toBeTrue()
        ->and(DestinationResource::canAccess())->toBeTrue()
        ->and(ServiceResource::canAccess())->toBeTrue()
        ->and(OfficeResource::canAccess())->toBeTrue()
        ->and(PartnerResource::canAccess())->toBeTrue()
        ->and(FaqResource::canAccess())->toBeTrue()
        ->and(TestimonialResource::canAccess())->toBeTrue();
});

test('admin dashboard renders travel operations widget', function () {
    $admin = User::factory()->create([
        'role' => Role::Admin,
    ]);

    Package::factory()->count(2)->create();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertSuccessful()
        ->assertSee('Travel operations overview')
        ->assertSee('Active packages');
});
