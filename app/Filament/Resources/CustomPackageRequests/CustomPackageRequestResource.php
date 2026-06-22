<?php

namespace App\Filament\Resources\CustomPackageRequests;

use App\Enum\CustomPackageRequestStatus;
use App\Filament\Resources\CustomPackageRequests\Pages\CreateCustomPackageRequest;
use App\Filament\Resources\CustomPackageRequests\Pages\EditCustomPackageRequest;
use App\Filament\Resources\CustomPackageRequests\Pages\ListCustomPackageRequests;
use App\Filament\Resources\CustomPackageRequests\Pages\ViewCustomPackageRequest;
use App\Filament\Resources\CustomPackageRequests\Schemas\CustomPackageRequestForm;
use App\Filament\Resources\CustomPackageRequests\Schemas\CustomPackageRequestInfolist;
use App\Filament\Resources\CustomPackageRequests\Tables\CustomPackageRequestsTable;
use App\Models\CustomPackageRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CustomPackageRequestResource extends Resource
{
    protected static ?string $model = CustomPackageRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Custom Requests';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->where('status', CustomPackageRequestStatus::New->value)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function form(Schema $schema): Schema
    {
        return CustomPackageRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomPackageRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomPackageRequestsTable::configure($table);
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
            'index' => ListCustomPackageRequests::route('/'),
            'create' => CreateCustomPackageRequest::route('/create'),
            'view' => ViewCustomPackageRequest::route('/{record}'),
            'edit' => EditCustomPackageRequest::route('/{record}/edit'),
        ];
    }
}
