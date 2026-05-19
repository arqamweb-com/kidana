<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Enum\PaymentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment')
                    ->schema([
                        Select::make('booking_id')
                            ->relationship('booking', 'customer_name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('provider')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('merchant_ref_number')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('fawry_reference_number')
                            ->maxLength(255),
                        Select::make('status')
                            ->options(PaymentStatus::options())
                            ->required(),
                        TextInput::make('amount')
                            ->numeric()
                            ->required(),
                        TextInput::make('currency')
                            ->maxLength(3)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
