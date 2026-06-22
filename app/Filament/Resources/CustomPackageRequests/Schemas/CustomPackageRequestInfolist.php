<?php

namespace App\Filament\Resources\CustomPackageRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CustomPackageRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('phone')
                    ->placeholder('-'),
                TextEntry::make('destination')
                    ->placeholder('-'),
                TextEntry::make('travel_type')
                    ->placeholder('-'),
                TextEntry::make('travelers')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('travel_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('budget')
                    ->placeholder('-'),
                TextEntry::make('accommodation')
                    ->placeholder('-'),
                TextEntry::make('duration')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('locale'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
