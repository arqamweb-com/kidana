<?php

namespace App\Filament\Resources\ApiRequestLogs;

use App\Filament\Resources\ApiRequestLogs\Pages\ListApiRequestLogs;
use App\Filament\Resources\ApiRequestLogs\Tables\ApiRequestLogsTable;
use App\Models\ApiRequestLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ApiRequestLogResource extends Resource
{
    protected static ?string $model = ApiRequestLog::class;

    protected static ?string $slug = 'api-request-logs';

    protected static ?string $modelLabel = 'API request';

    protected static ?string $navigationLabel = 'API request log';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static string|UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 6;

    public static function canAccess(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return ApiRequestLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApiRequestLogs::route('/'),
        ];
    }
}
