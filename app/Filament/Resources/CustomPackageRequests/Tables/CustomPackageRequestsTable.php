<?php

namespace App\Filament\Resources\CustomPackageRequests\Tables;

use App\Enum\CustomPackageRequestStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CustomPackageRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('destination')
                    ->searchable(),
                TextColumn::make('travel_type')
                    ->toggleable(),
                TextColumn::make('travelers')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('travel_date')
                    ->date()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('budget')
                    ->toggleable(),
                TextColumn::make('accommodation')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('duration')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state)
                    ->color(fn ($state): string => $state?->getColor() ?? 'gray')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(CustomPackageRequestStatus::options()),
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
