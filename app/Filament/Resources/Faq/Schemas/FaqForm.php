<?php

namespace App\Filament\Resources\Faq\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('FAQ Details')
                        ->schema([
                            TextInput::make('title')
                                ->label('Question')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            Textarea::make('answer')
                                ->label('Answer')
                                ->required()
                                ->rows(4)
                                ->columnSpanFull(),

                            TagsInput::make('tags')
                                ->placeholder('home, service, package')
                                ->suggestions([
                                    'home',
                                    'service',
                                    'package',
                                ])
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
