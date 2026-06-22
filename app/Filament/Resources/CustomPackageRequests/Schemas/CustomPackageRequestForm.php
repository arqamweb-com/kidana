<?php

namespace App\Filament\Resources\CustomPackageRequests\Schemas;

use App\Enum\CustomPackageRequestStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomPackageRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('destination'),
                TextInput::make('travel_type'),
                TextInput::make('travelers')
                    ->numeric(),
                DatePicker::make('travel_date'),
                TextInput::make('budget'),
                TextInput::make('accommodation'),
                TextInput::make('duration'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(CustomPackageRequestStatus::class)
                    ->default('new')
                    ->required(),
                TextInput::make('locale')
                    ->required()
                    ->default('en'),
            ]);
    }
}
