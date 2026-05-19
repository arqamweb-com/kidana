<?php

namespace App\Filament\Resources\Package;

use App\Filament\Resources\Package\Pages\CreatePackage;
use App\Filament\Resources\Package\Pages\EditPackage;
use App\Filament\Resources\Package\Pages\ListPackages;
use App\Filament\Resources\Package\Schemas\PackageForm;
use App\Filament\Resources\Package\Tables\PackageTable;
use App\Models\Package;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

class PackageResource extends Resource
{
    use Translatable;

    protected static ?string $model = Package::class;

    protected static ?string $slug = 'packages';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackageTable::configure($table);
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
            'index' => ListPackages::route('/'),
            'create' => CreatePackage::route('/create'),
            'edit' => EditPackage::route('/{record}/edit'),
        ];
    }
}
