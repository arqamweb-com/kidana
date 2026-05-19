<?php

namespace App\Filament\Resources\Partner;

use App\Filament\Resources\Partner\Pages\CreatePartner;
use App\Filament\Resources\Partner\Pages\EditPartner;
use App\Filament\Resources\Partner\Pages\ListPartners;
use App\Filament\Resources\Partner\Schemas\PartnerForm;
use App\Filament\Resources\Partner\Tables\PartnerTable;
use App\Models\Partner;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use LaraZeus\SpatieTranslatable\Resources\Concerns\Translatable;
use UnitEnum;

class PartnerResource extends Resource
{
    use Translatable;

    protected static ?string $model = Partner::class;

    protected static ?string $slug = 'partners';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Engagement';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PartnerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnerTable::configure($table);
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
            'index' => ListPartners::route('/'),
            'create' => CreatePartner::route('/create'),
            'edit' => EditPartner::route('/{record}/edit'),
        ];
    }
}
