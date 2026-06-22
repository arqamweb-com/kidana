<?php

namespace App\Filament\Resources\CustomPackageRequests\Pages;

use App\Filament\Resources\CustomPackageRequests\CustomPackageRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomPackageRequest extends ViewRecord
{
    protected static string $resource = CustomPackageRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
