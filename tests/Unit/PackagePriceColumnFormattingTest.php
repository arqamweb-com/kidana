<?php

use Filament\Tables\Columns\TextColumn;

test('package prices are formatted without decimals or separators', function () {
    $column = TextColumn::make('price')
        ->numeric(decimalPlaces: 0, thousandsSeparator: '');

    expect($column->formatState('240000.00'))->toBe('240000');
});
