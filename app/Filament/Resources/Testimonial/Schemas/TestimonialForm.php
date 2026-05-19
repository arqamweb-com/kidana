<?php

namespace App\Filament\Resources\Testimonial\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Testimonial Details')
                        ->schema([
                            TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('position')
                                ->label('Position')
                                ->required()
                                ->maxLength(255),

                            RichEditor::make('testimonial')
                                ->label('Testimonial')
                                ->required()
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
