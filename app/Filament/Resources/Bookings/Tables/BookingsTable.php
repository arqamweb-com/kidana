<?php

namespace App\Filament\Resources\Bookings\Tables;

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Models\Booking;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['package', 'hotel', 'hotelRoom']))
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_email')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('customer_mobile')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('booked_item')
                    ->label('Booked item')
                    ->state(fn (Booking $record): string => $record->bookedItemName())
                    ->description(fn (Booking $record): ?string => $record->isHotelBooking()
                        ? $record->check_in?->format('M j').' → '.$record->check_out?->format('M j, Y')
                        : null),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state)
                    ->color(fn ($state): string => $state?->getColor() ?? 'gray')
                    ->sortable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state)
                    ->toggleable(),
                TextColumn::make('total_amount')
                    ->money('EGP')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(BookingStatus::options()),
                SelectFilter::make('type')
                    ->options(PackageOrderAction::options()),
                TernaryFilter::make('hotel_bookings')
                    ->label('Booking kind')
                    ->placeholder('All bookings')
                    ->trueLabel('Hotels')
                    ->falseLabel('Packages')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('hotel_id'),
                        false: fn (Builder $query) => $query->whereNull('hotel_id'),
                    ),
                SelectFilter::make('hotel_id')
                    ->label('Hotel')
                    ->relationship('hotel', 'name')
                    ->searchable()
                    ->preload(),
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
