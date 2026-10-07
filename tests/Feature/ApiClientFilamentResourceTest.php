<?php

use App\Enum\ApiAbility;
use App\Enum\ApiPaymentMode;
use App\Enum\BookingSource;
use App\Enum\Role;
use App\Filament\Resources\ApiClients\ApiClientResource;
use App\Filament\Resources\ApiClients\Pages\CreateApiClient;
use App\Filament\Resources\ApiClients\Pages\EditApiClient;
use App\Filament\Resources\ApiClients\RelationManagers\TokensRelationManager;
use App\Filament\Resources\ApiClients\Widgets\ApiClientStatsWidget;
use App\Filament\Resources\ApiRequestLogs\ApiRequestLogResource;
use App\Filament\Resources\ApiRequestLogs\Pages\ListApiRequestLogs;
use App\Filament\Resources\Bookings\Pages\ListBookings;
use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use App\Models\Booking;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create(['role' => Role::Admin]));
});

test('only admins can manage api partners and read the request log', function () {
    expect(ApiClientResource::canAccess())->toBeTrue()
        ->and(ApiRequestLogResource::canAccess())->toBeTrue();

    $this->get(ApiClientResource::getUrl('index'))->assertSuccessful();
    $this->get(ApiRequestLogResource::getUrl('index'))->assertSuccessful();

    $this->actingAs(User::factory()->create(['role' => Role::Editor]));

    expect(ApiClientResource::canAccess())->toBeFalse()
        ->and(ApiRequestLogResource::canAccess())->toBeFalse();
});

test('an api partner can be created from the dashboard', function () {
    Livewire::test(CreateApiClient::class)
        ->fillForm([
            'name' => 'Sky Travel',
            'contact_email' => 'tech@sky.test',
            'payment_mode' => ApiPaymentMode::Fawry->value,
            'markup_percent' => 7.5,
            'allowed_abilities' => [ApiAbility::CatalogRead->value],
            'ip_allowlist' => ['203.0.113.10'],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(ApiClient::sole())
        ->name->toBe('Sky Travel')
        ->payment_mode->toBe(ApiPaymentMode::Fawry)
        ->markup_percent->toBe('7.50')
        ->allowed_abilities->toBe([ApiAbility::CatalogRead->value])
        ->ip_allowlist->toBe(['203.0.113.10'])
        ->webhook_secret->toHaveLength(40);
});

test('generating a token shows it once and stores only its hash', function () {
    $client = ApiClient::factory()->create(['allowed_abilities' => [ApiAbility::CatalogRead->value]]);

    Livewire::test(EditApiClient::class, ['record' => $client->getRouteKey()])
        ->callAction('generateToken', data: [
            'name' => 'Production',
            'abilities' => [ApiAbility::CatalogRead->value],
        ])
        ->assertHasNoFormErrors()
        ->assertNotified('Token created. Copy it now, it will not be shown again.');

    $token = $client->tokens()->sole();

    expect($token)
        ->name->toBe('Production')
        ->abilities->toBe([ApiAbility::CatalogRead->value])
        ->token->toHaveLength(64);
});

test('a token can be revoked', function () {
    $client = ApiClient::factory()->create();
    $client->createToken('Old');

    Livewire::test(TokensRelationManager::class, ['ownerRecord' => $client, 'pageClass' => EditApiClient::class])
        ->callAction(TestAction::make(DeleteAction::class)->table($client->tokens()->sole()));

    expect($client->tokens()->count())->toBe(0);
});

test('the partner stats widget renders for a client', function () {
    $client = ApiClient::factory()->create();
    Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => $client->id]);

    Livewire::test(ApiClientStatsWidget::class, ['record' => $client])
        ->assertSee('Bookings')
        ->assertSee('Requests (24h)');
});

test('the request log lists api calls', function () {
    $client = ApiClient::factory()->create();
    $log = ApiRequestLog::create(['api_client_id' => $client->id, 'method' => 'GET', 'path' => '/api/v1/packages', 'status' => 200, 'duration_ms' => 12]);

    Livewire::test(ListApiRequestLogs::class)
        ->assertCanSeeTableRecords([$log]);
});

test('bookings can be filtered by source and partner', function () {
    $client = ApiClient::factory()->create();
    $apiBooking = Booking::factory()->create(['source' => BookingSource::Api, 'api_client_id' => $client->id]);
    $webBooking = Booking::factory()->create();

    Livewire::test(ListBookings::class)
        ->filterTable('source', BookingSource::Api->value)
        ->assertCanSeeTableRecords([$apiBooking])
        ->assertCanNotSeeTableRecords([$webBooking])
        ->resetTableFilters()
        ->filterTable('api_client_id', $client->id)
        ->assertCanSeeTableRecords([$apiBooking])
        ->assertCanNotSeeTableRecords([$webBooking]);
});
