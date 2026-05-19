<?php

use App\Filament\Resources\Service\Schemas\ServiceForm;
use Tests\TestCase;

uses(TestCase::class);

test('service benefit heroicon options render text labels', function () {
    $method = new ReflectionMethod(ServiceForm::class, 'benefitIconOptions');
    $method->setAccessible(true);

    $options = $method->invoke(null);

    expect($options)
        ->toHaveKey('heroicon-o-check-circle')
        ->and($options['heroicon-o-check-circle'])
        ->toBe('Check Circle');
});
