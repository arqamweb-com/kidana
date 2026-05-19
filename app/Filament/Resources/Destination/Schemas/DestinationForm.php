<?php

namespace App\Filament\Resources\Destination\Schemas;

use App\Filament\Support\FileUploadStorage;
use App\Filament\Support\SlugGenerator;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class DestinationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Group::make([
                    Section::make('Destination Details')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(SlugGenerator::updateSlug())
                                ->maxLength(255),

                            TextInput::make('slug')
                                ->required()
                                ->dehydrateStateUsing(SlugGenerator::normalize(...))
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),

                            FileUpload::make('image_url')
                                ->label('Image')
                                ->image()
                                ->disk('public')
                                ->directory('destinations')
                                ->visibility('public')
                                ->saveUploadedFileUsing(FileUploadStorage::storePublicly())
                                ->imagePreviewHeight('250')
                                ->maxSize(2048),
                        ])
                        ->columns(2)
                        ->columnSpan(2)
                        ->collapsible(),


                    Section::make('Visibility & Sorting')
                        ->schema([
                            Toggle::make('is_active')
                                ->label('Active')
                                ->default(true),

                            TextInput::make('sort_order')
                                ->label('Sort Order')
                                ->integer()
                                ->default(0)
                                ->minValue(0),
                        ])
                        ->columnSpan(1)
                        ->columns(1)
                        ->collapsible(),
                ])->columns(3)->columnSpanFull()
            ])->columns(3);
    }
}
