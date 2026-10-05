<?php

namespace App\Filament\Resources\Hotel\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class HotelTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('destination')->withCount('rooms')->withMin('rooms', 'price_per_night'))
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->disk('public')
                    ->toggleable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('destination.name')
                    ->label('Destination')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('stars')
                    ->formatStateUsing(fn (?int $state): string => $state ? str_repeat('★', $state) : '—')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('rooms_count')
                    ->label('Room types')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('rooms_min_price_per_night')
                    ->label('From / night')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('order_action')
                    ->label('Booking action')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state)
                    ->toggleable(isToggledHiddenByDefault: true),
                ToggleColumn::make('is_active')
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('destination_id')
                    ->label('Destination')
                    ->relationship('destination', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('stars')
                    ->options([1 => '1', 2 => '2', 3 => '3', 4 => '4', 5 => '5']),
            ])
            ->deferFilters(false)
            ->defaultSort('sort_order')
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
