<?php

namespace App\Filament\Resources\Hotel;

use App\Filament\Resources\Hotel\Pages\CreateHotel;
use App\Filament\Resources\Hotel\Pages\EditHotel;
use App\Filament\Resources\Hotel\Pages\ListHotels;
use App\Filament\Resources\Hotel\RelationManagers\RoomsRelationManager;
use App\Filament\Resources\Hotel\Schemas\HotelForm;
use App\Filament\Resources\Hotel\Tables\HotelTable;
use App\Models\Hotel;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

class HotelResource extends Resource
{
    use Translatable;

    protected static ?string $model = Hotel::class;

    protected static ?string $slug = 'hotels';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return HotelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HotelTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RoomsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHotels::route('/'),
            'create' => CreateHotel::route('/create'),
            'edit' => EditHotel::route('/{record}/edit'),
        ];
    }
}
