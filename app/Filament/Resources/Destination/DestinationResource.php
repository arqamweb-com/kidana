<?php

namespace App\Filament\Resources\Destination;

use App\Filament\Resources\Destination\Pages\CreateDestination;
use App\Filament\Resources\Destination\Pages\EditDestination;
use App\Filament\Resources\Destination\Pages\ListDestinations;
use App\Filament\Resources\Destination\Schemas\DestinationForm;
use App\Filament\Resources\Destination\Tables\DestinationTable;
use App\Models\Destination;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;
class DestinationResource extends Resource
{
    use Translatable;

    protected static ?string $model = Destination::class;

    protected static ?string $slug = 'destinations';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::MapPin;

    protected static string|UnitEnum|null $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DestinationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DestinationTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDestinations::route('/'),
            'create' => CreateDestination::route('/create'),
            'edit' => EditDestination::route('/{record}/edit'),
        ];
    }
}
