<?php

namespace App\Filament\Resources\Package\Schemas;

use App\Enum\PackageOrderAction;
use App\Filament\Support\FileUploadStorage;
use App\Filament\Support\SlugGenerator;
use App\Models\Faq;
use App\Models\Testimonial;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Package Details')
                        ->schema([
                            Group::make()
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Package Name')
                                        ->required()
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(SlugGenerator::updateSlug())
                                        ->maxLength(255),
                                    TextInput::make('slug')
                                        ->required()
                                        ->dehydrateStateUsing(SlugGenerator::normalize(...))
                                        ->maxLength(255)
                                        ->unique(ignoreRecord: true),
                                    RichEditor::make('description')
                                        ->label('Description')
                                        ->columnSpanFull(),
                                ])
                                ->columns(2),
                        ])->collapsible(),
                    Section::make('Basic Information')
                        ->schema([
                            Select::make('service_id')
                                ->label('Service')
                                ->relationship('service', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('destination_id')
                                ->label('Destination')
                                ->relationship('destination', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            TextInput::make('location_label')
                                ->maxLength(255),
                            TextInput::make('price')
                                ->required()
                                ->inputMode('numeric')
                                ->rules(['integer', 'min:0'])
                                ->dehydrateStateUsing(fn ($state) => (int) preg_replace('/[^\d]/', '', (string) $state)),
                            Select::make('order_action')
                                ->label('Order button action')
                                ->options(PackageOrderAction::options())
                                ->required()
                                ->default(PackageOrderAction::CustomForm->value),
                            TextInput::make('max_guests')
                                ->integer()
                                ->minValue(1),
                            TagsInput::make('tags')
                                ->placeholder('home, featured, umrah')
                                ->suggestions([
                                    'home',
                                    'featured',
                                    'umrah',
                                    'hajj',
                                ])
                                ->columnSpanFull(),
                        ])
                        ->collapsible()
                        ->columns(2),
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
                    Section::make('Trip Itinerary')
                        ->schema([
                            Repeater::make('itinerary')
                                ->schema([
                                    TextInput::make('day_label')
                                        ->label('Day Label')
                                        ->required(),

                                    Select::make('icon')
                                        ->label('Heroicon')
                                        ->options(self::IconOptions())
                                        ->searchable()
                                        ->required(),

                                    TextInput::make('title')
                                        ->required(),

                                    Textarea::make('description')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ])
                                ->reorderable()
                                ->itemLabel(fn ($state) => $state['title'] ?? 'Day')
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->collapsible(),

                    Section::make('What’s Included / Not Included')
                        ->schema([
                            Repeater::make('included_items')
                                ->label("What's Included")
                                ->schema([
                                    Select::make('icon')
                                        ->label('Heroicon')
                                        ->options(self::IconOptions())
                                        ->searchable()
                                        ->default('heroicon-o-check')
                                        ->required(),

                                    TextInput::make('title')
                                        ->label('Item')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->default([])
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn ($state) => $state['title'] ?? 'Included item')
                                ->columnSpanFull(),

                            Repeater::make('excluded_items')
                                ->label("What's Not Included")
                                ->schema([
                                    Select::make('icon')
                                        ->label('Heroicon')
                                        ->options(self::IconOptions())
                                        ->searchable()
                                        ->default('heroicon-o-x-mark')
                                        ->required(),

                                    TextInput::make('title')
                                        ->label('Item')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->default([])
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn ($state) => $state['title'] ?? 'Excluded item')
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->collapsible(),

                    Section::make('Package Highlights')
                        ->schema([
                            Repeater::make('highlights')
                                ->label('Highlights')
                                ->schema([
                                    Select::make('icon')
                                        ->label('Heroicon')
                                        ->options(self::IconOptions())
                                        ->searchable()
                                        ->default('heroicon-o-star')
                                        ->required(),
                                    TextInput::make('title')
                                        ->label('Title')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->default([])
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn ($state) => $state['title'] ?? 'Highlight')
                                ->columnSpanFull(),
                        ])
                        ->collapsible(),

                    Section::make('FAQs & Testimonials')
                        ->schema([
                            Select::make('faqs')
                                ->label('FAQs')
                                ->multiple()
                                ->relationship(titleAttribute: 'title')
                                ->getOptionLabelFromRecordUsing(fn (Faq $record): string => $record->title)
                                ->searchable()
                                ->preload()
                                ->columnSpanFull(),
                            Select::make('testimonials')
                                ->label('Testimonials')
                                ->multiple()
                                ->relationship(titleAttribute: 'name')
                                ->getOptionLabelFromRecordUsing(fn (Testimonial $record): string => $record->name)
                                ->searchable()
                                ->preload()
                                ->columnSpanFull(),
                        ])
                        ->collapsible(),
                ])->columnSpan(2),
                Group::make([
                    Section::make('Features image')
                        ->schema([
                            FileUpload::make('image_url')
                                ->label('Image')
                                ->image()
                                ->disk('public')
                                ->directory('packages')
                                ->visibility('public')
                                ->saveUploadedFileUsing(FileUploadStorage::storePublicly())
                                ->imagePreviewHeight('250')
                                ->maxSize(2048),
                        ])->collapsible(),
                    Section::make('Trip Schedule')
                        ->schema([
                            DatePicker::make('start_date'),
                            DatePicker::make('end_date')
                                ->afterOrEqual('start_date'),
                            TextInput::make('days')
                                ->integer()
                                ->minValue(1),
                        ])->collapsible(),
                    Section::make('Visibility & Sorting')
                        ->schema([
                            Toggle::make('is_active')
                                ->default(true),
                            TextInput::make('sort_order')
                                ->integer()
                                ->default(0),
                        ])->collapsible(),
                ])->columnSpan(1),
            ])->columns(3);

    }

    /**
     * @return array<string, string>
     */
    protected static function IconOptions(): array
    {
        return collect(Heroicon::cases())
            ->filter(fn (Heroicon $icon): bool => str_starts_with($icon->value, 'o-'))
            ->mapWithKeys(fn (Heroicon $icon): array => [
                "heroicon-{$icon->value}" => (string) Str::of($icon->value)
                    ->after('o-')
                    ->replace('-', ' ')
                    ->title(),
            ])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    //    public static function includedIconOptions(): array
    //    {
    //        return [
    //            'heroicon-o-check' => 'Check',
    //            'heroicon-o-bed-double' => 'Hotel',
    //            'heroicon-o-utensils' => 'Meals',
    //            'heroicon-o-user-check' => 'Guide',
    //            'heroicon-o-compass' => 'Transport',
    //        ];
    //    }

    /**
     * @return array<string, string>
     */
    //    public static function excludedIconOptions(): array
    //    {
    //        return [
    //            'heroicon-o-x-mark' => 'Not Included',
    //            'heroicon-o-plane' => 'Flights',
    //            'heroicon-o-shopping-bag' => 'Shopping',
    //        ];
    //    }
}
