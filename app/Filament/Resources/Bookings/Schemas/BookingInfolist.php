<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Models\Booking;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Booking')
                    ->schema([
                        TextEntry::make('id')->label('Booking ID'),
                        TextEntry::make('reference')->copyable(),
                        TextEntry::make('package.name')
                            ->label('Package')
                            ->hidden(fn (Booking $record): bool => $record->isHotelBooking()),
                        TextEntry::make('hotel.name')
                            ->label('Hotel')
                            ->visible(fn (Booking $record): bool => $record->isHotelBooking()),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state)
                            ->color(fn ($state): string => $state?->getColor() ?? 'gray'),
                        TextEntry::make('type')
                            ->badge()
                            ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state),
                        TextEntry::make('total_amount')->money('EGP'),
                        TextEntry::make('created_at')->dateTime(),
                    ])
                    ->columns(2),
                Section::make('Partner API')
                    ->schema([
                        TextEntry::make('apiClient.name')->label('Partner'),
                        TextEntry::make('external_reference')->label('Partner reference')->placeholder('-'),
                    ])
                    ->columns(2)
                    ->visible(fn (Booking $record): bool => $record->isFromApi()),
                Section::make('Hotel Stay')
                    ->schema([
                        TextEntry::make('hotelRoom.name')->label('Room type'),
                        TextEntry::make('rooms'),
                        TextEntry::make('check_in')->date(),
                        TextEntry::make('check_out')->date(),
                        TextEntry::make('nights'),
                        TextEntry::make('adults'),
                        TextEntry::make('children'),
                    ])
                    ->columns(2)
                    ->visible(fn (Booking $record): bool => $record->isHotelBooking()),
                Section::make('Customer')
                    ->schema([
                        TextEntry::make('customer_name'),
                        TextEntry::make('customer_email'),
                        TextEntry::make('customer_mobile'),
                        TextEntry::make('guests'),
                        TextEntry::make('travel_date')->date(),
                        TextEntry::make('message')->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
