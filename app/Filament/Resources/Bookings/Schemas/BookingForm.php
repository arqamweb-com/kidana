<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Booking Details')
                    ->schema([
                        Select::make('package_id')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('type')
                            ->options(PackageOrderAction::options())
                            ->required(),
                        Select::make('status')
                            ->options(BookingStatus::options())
                            ->required(),
                        TextInput::make('total_amount')
                            ->numeric()
                            ->required(),
                        TextInput::make('currency')
                            ->maxLength(3)
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Customer')
                    ->schema([
                        TextInput::make('customer_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('customer_email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('customer_mobile')
                            ->required()
                            ->maxLength(32),
                        TextInput::make('guests')
                            ->integer()
                            ->minValue(1),
                        DatePicker::make('travel_date'),
                        Textarea::make('message')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
