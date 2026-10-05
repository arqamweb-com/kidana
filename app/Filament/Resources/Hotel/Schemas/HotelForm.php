<?php

namespace App\Filament\Resources\Hotel\Schemas;

use App\Enum\HotelBoardBasis;
use App\Enum\PackageOrderAction;
use App\Filament\Resources\Package\Schemas\PackageForm;
use App\Filament\Support\FileUploadStorage;
use App\Filament\Support\SlugGenerator;
use App\Models\Faq;
use App\Models\Testimonial;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HotelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make('Hotel Details')
                        ->schema([
                            TextInput::make('name')
                                ->label('Hotel Name')
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
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->collapsible(),
                    Section::make('Basic Information')
                        ->schema([
                            Select::make('destination_id')
                                ->label('Destination')
                                ->relationship('destination', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),
                            Select::make('stars')
                                ->options([
                                    1 => '1 star',
                                    2 => '2 stars',
                                    3 => '3 stars',
                                    4 => '4 stars',
                                    5 => '5 stars',
                                ]),
                            TextInput::make('address')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('map_url')
                                ->label('Google Maps link')
                                ->url()
                                ->maxLength(2048)
                                ->columnSpanFull(),
                            Select::make('board_basis')
                                ->label('Meal plan')
                                ->options(HotelBoardBasis::options()),
                            Select::make('order_action')
                                ->label('Booking button action')
                                ->options(PackageOrderAction::options())
                                ->required()
                                ->default(PackageOrderAction::CustomForm->value),
                            TimePicker::make('check_in_time')
                                ->seconds(false)
                                ->format('H:i'),
                            TimePicker::make('check_out_time')
                                ->seconds(false)
                                ->format('H:i'),
                            TagsInput::make('tags')
                                ->placeholder('home, featured')
                                ->suggestions(['home', 'featured'])
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->collapsible(),
                    Section::make('Gallery')
                        ->schema([
                            Repeater::make('gallery')
                                ->schema([
                                    FileUpload::make('image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('hotels/gallery')
                                        ->visibility('public')
                                        ->saveUploadedFileUsing(FileUploadStorage::storePublicly())
                                        ->required(),
                                    TextInput::make('caption'),
                                ])
                                ->columns(2)
                                ->default([])
                                ->reorderable()
                                ->columnSpanFull(),
                        ])
                        ->collapsible()
                        ->collapsed(),
                    Section::make('Amenities')
                        ->schema([
                            Repeater::make('amenities')
                                ->hiddenLabel()
                                ->schema([
                                    Select::make('icon')
                                        ->label('Heroicon')
                                        ->options(PackageForm::IconOptions())
                                        ->searchable()
                                        ->default('heroicon-o-check')
                                        ->required(),
                                    TextInput::make('title')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->columns(2)
                                ->default([])
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn ($state) => $state['title'] ?? 'Amenity')
                                ->columnSpanFull(),
                        ])
                        ->collapsible(),
                    Section::make('Policies')
                        ->schema([
                            Repeater::make('policies')
                                ->hiddenLabel()
                                ->schema([
                                    TextInput::make('title')
                                        ->required()
                                        ->maxLength(255),
                                    Textarea::make('description')
                                        ->rows(2),
                                ])
                                ->default([])
                                ->reorderable()
                                ->collapsible()
                                ->itemLabel(fn ($state) => $state['title'] ?? 'Policy')
                                ->columnSpanFull(),
                            Textarea::make('cancellation_policy')
                                ->rows(4)
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
                                ->preload(),
                            Select::make('testimonials')
                                ->multiple()
                                ->relationship(titleAttribute: 'name')
                                ->getOptionLabelFromRecordUsing(fn (Testimonial $record): string => $record->name)
                                ->searchable()
                                ->preload(),
                        ])
                        ->collapsible()
                        ->collapsed(),
                ])->columnSpan(2),
                Group::make([
                    Section::make('Featured image')
                        ->schema([
                            FileUpload::make('image_url')
                                ->label('Image')
                                ->image()
                                ->disk('public')
                                ->directory('hotels')
                                ->visibility('public')
                                ->saveUploadedFileUsing(FileUploadStorage::storePublicly())
                                ->imagePreviewHeight('250')
                                ->maxSize(2048),
                        ])
                        ->collapsible(),
                    Section::make('Visibility & Sorting')
                        ->schema([
                            Toggle::make('is_active')
                                ->default(true),
                            TextInput::make('sort_order')
                                ->integer()
                                ->default(0),
                        ])
                        ->collapsible(),
                ])->columnSpan(1),
            ])
            ->columns(3);
    }
}
