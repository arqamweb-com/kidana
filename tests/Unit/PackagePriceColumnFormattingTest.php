<?php

use Filament\Tables\Columns\TextColumn;

test('package prices are formatted as whole numbers without separators', function () {
    $column = TextColumn::make('price')
        ->numeric(decimalPlaces: 0, thousandsSeparator: '');

    expect($column->formatState(240000))->toBe('240000');
});
