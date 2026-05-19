<?php

namespace App\Filament\Resources\Bookings\Schemas;

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
                        TextEntry::make('package.name')->label('Package'),
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
