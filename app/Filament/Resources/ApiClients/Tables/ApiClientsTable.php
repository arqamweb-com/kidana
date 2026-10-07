<?php

namespace App\Filament\Resources\ApiClients\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ApiClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount('bookings'))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact_email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('payment_mode')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state),
                TextColumn::make('markup_percent')
                    ->label('Markup')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('bookings_count')
                    ->label('Bookings')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
