<?php

namespace App\Filament\Resources\Hotel\RelationManagers;

use App\Filament\Support\FileUploadStorage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Livewire\Attributes\Reactive;

class RoomsRelationManager extends RelationManager
{
    use Translatable;

    protected static string $relationship = 'rooms';

    protected static ?string $title = 'Room types';

    #[Reactive]
    public ?string $activeLocale = null;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Room')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('bed_type')
                            ->placeholder('1 King bed')
                            ->maxLength(255),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        FileUpload::make('image_url')
                            ->label('Image')
                            ->image()
                            ->disk('public')
                            ->directory('hotels/rooms')
                            ->visibility('public')
                            ->saveUploadedFileUsing(FileUploadStorage::storePublicly())
                            ->maxSize(2048)
                            ->columnSpanFull(),
                        TagsInput::make('amenities')
                            ->placeholder('Sea view, Balcony, Mini bar')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Capacity & Pricing')
                    ->schema([
                        TextInput::make('price_per_night')
                            ->label('Price per night (EGP)')
                            ->required()
                            ->integer()
                            ->minValue(0),
                        TextInput::make('quantity')
                            ->label('Rooms available')
                            ->helperText('How many rooms of this type can be booked for the same night.')
                            ->required()
                            ->integer()
                            ->minValue(0)
                            ->default(1),
                        TextInput::make('max_adults')
                            ->required()
                            ->integer()
                            ->minValue(1)
                            ->default(2),
                        TextInput::make('max_children')
                            ->required()
                            ->integer()
                            ->minValue(0)
                            ->default(0),
                        TextInput::make('size_sqm')
                            ->label('Size (m²)')
                            ->integer()
                            ->minValue(1),
                        TextInput::make('sort_order')
                            ->integer()
                            ->default(0),
                        Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->disk('public'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('price_per_night')
                    ->label('Price / night')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '')
                    ->sortable(),
                TextColumn::make('max_adults')
                    ->label('Adults'),
                TextColumn::make('max_children')
                    ->label('Children'),
                TextColumn::make('quantity')
                    ->label('Rooms')
                    ->sortable(),
                ToggleColumn::make('is_active'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
