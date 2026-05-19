<?php

namespace App\Filament\Resources\Package\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Action;
use Filament\Tables\Columns\ToggleColumn;

class PackageTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('service.name')
                    ->label('Service')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('destination.name')
                    ->label('Destination')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('tags')
                    ->badge()
                    ->separator(',')
                    ->toggleable(),
                TextColumn::make('price')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('max_guests')
                    ->sortable()
                    ->toggleable(),
                ToggleColumn::make('is_active')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('service_id')
                    ->label('Service')
                    ->relationship('service', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('destination_id')
                    ->label('Destination')
                    ->relationship('destination', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('date_range')->form([
                    DatePicker::make('start_from')
                        ->label('Start from'),

                    DatePicker::make('start_until')
                        ->label('Start until'),
                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['start_from'],
                            fn(Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                        )
                        ->when(
                            $data['start_until'],
                            fn(Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                        );
                }),
            ])
            ->deferFilters(false)
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
