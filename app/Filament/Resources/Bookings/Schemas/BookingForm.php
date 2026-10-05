<?php

namespace App\Filament\Resources\Bookings\Schemas;

use App\Enum\BookingStatus;
use App\Enum\PackageOrderAction;
use App\Models\Booking;
use App\Models\HotelRoom;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

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
                            ->requiredWithout('hotel_id')
                            ->hidden(fn (Get $get): bool => filled($get('hotel_id'))),
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
                Section::make('Hotel Stay')
                    ->schema([
                        Select::make('hotel_id')
                            ->relationship('hotel', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('hotel_room_id', null)),
                        Select::make('hotel_room_id')
                            ->label('Room type')
                            ->relationship(
                                'hotelRoom',
                                'name',
                                fn (Builder $query, Get $get) => $query->where('hotel_id', $get('hotel_id')),
                            )
                            ->getOptionLabelFromRecordUsing(fn (HotelRoom $record): string => $record->name)
                            ->requiredWith('hotel_id'),
                        DatePicker::make('check_in')
                            ->requiredWith('hotel_id'),
                        DatePicker::make('check_out')
                            ->requiredWith('hotel_id')
                            ->after('check_in'),
                        TextInput::make('rooms')
                            ->integer()
                            ->minValue(1),
                        TextInput::make('nights')
                            ->integer()
                            ->minValue(1),
                        TextInput::make('adults')
                            ->integer()
                            ->minValue(1),
                        TextInput::make('children')
                            ->integer()
                            ->minValue(0),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(fn (?Booking $record): bool => $record !== null && ! $record->isHotelBooking()),
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
