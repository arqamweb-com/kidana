<?php

use App\Mail\ContactMessageReceived;
use Illuminate\Support\Facades\Mail;

it('shows the contact page', function () {
    $this->get('/en/contact')->assertOk();
});

it('sends a contact email and redirects with success', function () {
    Mail::fake();

    $this->post('/en/contact', [
        'name' => 'Ahmed Test',
        'email' => 'ahmed@example.com',
        'phone' => '+201012345678',
        'message' => 'I would like to book a package.',
    ])
        ->assertRedirect('/en/contact')
        ->assertSessionHas('success');

    Mail::assertSent(ContactMessageReceived::class, function (ContactMessageReceived $mail) {
        return $mail->senderName === 'Ahmed Test'
            && $mail->senderEmail === 'ahmed@example.com'
            && $mail->body === 'I would like to book a package.';
    });
});

it('fails validation when required fields are missing', function () {
    Mail::fake();

    $this->post('/en/contact', [])
        ->assertSessionHasErrors(['name', 'email', 'message']);

    Mail::assertNothingSent();
});

it('fails validation with an invalid email', function () {
    Mail::fake();

    $this->post('/en/contact', [
        'name' => 'Ahmed Test',
        'email' => 'not-an-email',
        'message' => 'Hello',
    ])->assertSessionHasErrors(['email']);

    Mail::assertNothingSent();
});

it('sends without phone since it is optional', function () {
    Mail::fake();

    $this->post('/en/contact', [
        'name' => 'No Phone',
        'email' => 'noPhone@example.com',
        'message' => 'Message without phone.',
    ])
        ->assertRedirect('/en/contact')
        ->assertSessionHas('success');

    Mail::assertSent(ContactMessageReceived::class);
});
