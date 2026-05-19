<?php

namespace App\Filament\Resources\Service\Schemas;

use App\Filament\Support\FileUploadStorage;
use App\Filament\Support\SlugGenerator;
use App\Models\Faq;
use App\Models\Testimonial;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Hero Section')
                        ->schema([
                            Group::make()
                                ->schema([
                                    TextInput::make('hero_title')->maxLength(255),
                                    TextInput::make('hero_subtitle')->maxLength(255),
                                    Textarea::make('hero_description')
                                        ->rows(4)
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                            FileUpload::make('hero_image')
                                ->image()
                                ->disk('public')
                                ->directory('services')
                                ->visibility('public')
                                ->saveUploadedFileUsing(FileUploadStorage::storePublicly())
                                ->imagePreviewHeight('250')
                                ->maxSize(2048)
                                ->required(),
                        ])
                        ->collapsible(),
                    Section::make('Introduction')
                        ->schema([
                            TextInput::make('intro_subtitle')->maxLength(255),
                            TextInput::make('intro_title')->maxLength(255),
                            Textarea::make('intro_text')
                                ->rows(4)
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->collapsible(),
                    Section::make('Benefits')
                        ->schema([
                            Repeater::make('benefits')
                                ->schema([
                                    Select::make('icon')
                                        ->label('Heroicon')
                                        ->options(self::benefitIconOptions())
                                        ->searchable()
                                        ->required(),
                                    TextInput::make('title')->required(),
                                    Textarea::make('desc')
                                        ->required()
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ])
                                ->default([])
                                ->columnSpanFull(),
                        ])
                        ->collapsible(),
                    Section::make('Gallery')
                        ->schema([
                            Repeater::make('gallery')
                                ->schema([
                                    FileUpload::make('image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('services/gallery')
                                        ->visibility('public')
                                        ->saveUploadedFileUsing(FileUploadStorage::storePublicly())
                                        ->required(),

                                    TextInput::make('caption'),
                                ])
                                ->columns(2)
                                ->default([])
                                ->columnSpanFull(),
                        ])
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Testimonials')
                        ->schema([
                            Select::make('testimonials')
                                ->label('Testimonials')
                                ->multiple()
                                ->relationship(titleAttribute: 'name')
                                ->getOptionLabelFromRecordUsing(fn (Testimonial $record): string => $record->name)
                                ->searchable()
                                ->preload(),
                            Select::make('faqs')
                                ->label('FAQs')
                                ->multiple()
                                ->relationship(titleAttribute: 'title')
                                ->getOptionLabelFromRecordUsing(fn (Faq $record): string => $record->title)
                                ->searchable()
                                ->preload(),
                        ])
                        ->collapsible(),
                ])->columnSpan(2),

                Group::make([
                    Section::make('Service Details')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(SlugGenerator::updateSlug()),
                            TextInput::make('slug')
                                ->required()
                                ->dehydrateStateUsing(SlugGenerator::normalize(...))
                                ->unique(ignoreRecord: true),
                            Textarea::make('description')->rows(4),
                        ])->collapsible(),
                    Section::make('Settings')
                        ->schema([
                            TextInput::make('icon'),
                            Toggle::make('is_active')->default(true),
                            TextInput::make('sort_order')->integer()->default(0),
                        ])->collapsible(),
                ])->columnSpan(1),
            ])->columns(3);
    }

    /**
     * @return array<string, string>
     */
    protected static function benefitIconOptions(): array
    {
        return collect(Heroicon::cases())
            ->filter(fn(Heroicon $icon): bool => str_starts_with($icon->value, 'o-'))
            ->mapWithKeys(fn(Heroicon $icon): array => [
                "heroicon-{$icon->value}" => (string) Str::of($icon->value)
                    ->after('o-')
                    ->replace('-', ' ')
                    ->title(),
            ])
            ->all();
    }
}
