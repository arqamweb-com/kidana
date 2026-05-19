<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('merchant_ref_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('fawry_reference_number')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('booking.customer_name')
                    ->label('Customer')
                    ->searchable(),
                TextColumn::make('booking.package.name')
                    ->label('Package')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state)
                    ->color(fn ($state): string => $state?->getColor() ?? 'gray')
                    ->sortable(),
                TextColumn::make('amount')
                    ->money('EGP')
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(\App\Enum\PaymentStatus::options()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
