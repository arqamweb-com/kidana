<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment')
                    ->schema([
                        TextEntry::make('merchant_ref_number'),
                        TextEntry::make('fawry_reference_number'),
                        TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn ($state): string => $state?->value ?? (string) $state)
                            ->color(fn ($state): string => $state?->getColor() ?? 'gray'),
                        TextEntry::make('amount')->money('EGP'),
                        TextEntry::make('fawry_fees')->money('EGP'),
                        TextEntry::make('paid_at')->dateTime(),
                    ])
                    ->columns(2),
                Section::make('Booking')
                    ->schema([
                        TextEntry::make('booking.id')->label('Booking ID'),
                        TextEntry::make('booking.customer_name')->label('Customer'),
                        TextEntry::make('booking.customer_email')->label('Email'),
                        TextEntry::make('booking.package.name')->label('Package'),
                    ])
                    ->columns(2),
            ]);
    }
}
