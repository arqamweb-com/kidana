<?php

namespace App\Filament\Resources\Partner\Schemas;

use App\Filament\Support\FileUploadStorage;
use App\Filament\Support\SlugGenerator;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Partner Details')
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
                                ->label('Logo')
                                ->image()
                                ->disk('public')
                                ->directory('partners')
                                ->visibility('public')
                                ->saveUploadedFileUsing(FileUploadStorage::storePublicly())
                                ->imagePreviewHeight('200')
                                ->maxSize(2048)
                                ->columnSpanFull(),
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
                        ->columns(1)
                        ->columnSpan(1)
                        ->collapsible(),
                ])
                    ->columns(3)
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}
