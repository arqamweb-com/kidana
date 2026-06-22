<?php

use App\Enum\CustomPackageRequestStatus;
use App\Mail\CustomPackageRequested;
use App\Models\CustomPackageRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

it('stores the request in the dashboard, emails it and redirects with success', function () {
    Mail::fake();

    $this->post('/en/book-now/custom-package', [
        'destination' => 'Egypt',
        'travel_type' => 'Guided Tours',
        'travelers' => 2,
        'travel_date' => '2026-09-01',
        'budget' => 'EGP 100,000 – 200,000',
        'accommodation' => '5-Star',
        'duration' => '6 – 9 Days',
        'name' => 'Sara Test',
        'email' => 'sara@example.com',
        'phone' => '+201012345678',
        'notes' => 'We prefer a private guide.',
    ])
        ->assertRedirect('/en/book-now')
        ->assertSessionHas('custom_package_success');

    $request = CustomPackageRequest::sole();

    expect($request->name)->toBe('Sara Test')
        ->and($request->email)->toBe('sara@example.com')
        ->and($request->destination)->toBe('Egypt')
        ->and($request->accommodation)->toBe('5-Star')
        ->and($request->travelers)->toBe(2)
        ->and($request->status)->toBe(CustomPackageRequestStatus::New)
        ->and($request->locale)->toBe('en');

    Mail::assertSent(CustomPackageRequested::class, function (CustomPackageRequested $mail) {
        return $mail->senderName === 'Sara Test'
            && $mail->senderEmail === 'sara@example.com'
            && $mail->details['Destination'] === 'Egypt'
            && $mail->details['Accommodation'] === '5-Star';
    });
});

it('fails validation when name and email are missing', function () {
    Mail::fake();

    $this->post('/en/book-now/custom-package', [
        'destination' => 'Egypt',
    ])->assertSessionHasErrors(['name', 'email']);

    Mail::assertNothingSent();
});

it('sends with only the required contact fields', function () {
    Mail::fake();

    $this->post('/en/book-now/custom-package', [
        'name' => 'Minimal',
        'email' => 'minimal@example.com',
    ])
        ->assertRedirect('/en/book-now')
        ->assertSessionHas('custom_package_success');

    Mail::assertSent(CustomPackageRequested::class);
});
