<?php

namespace App\Filament\Resources\Service;

use App\Filament\Resources\Service\Pages\CreateService;
use App\Filament\Resources\Service\Pages\EditService;
use App\Filament\Resources\Service\Pages\ListServices;
use App\Filament\Resources\Service\RelationManagers\PackageRelationManager;
use App\Filament\Resources\Service\Schemas\ServiceForm;
use App\Filament\Resources\Service\Tables\ServiceTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

//use Filament\Resources\Concerns\Translatable;

class ServiceResource extends Resource
{
    use Translatable;

    protected static ?string $model = Service::class;

    protected static ?string $slug = 'services';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PackageRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
