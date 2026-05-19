<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application redirects to the default locale', function () {
    $response = $this->get('/');

    $response->assertRedirect('/en');
});
