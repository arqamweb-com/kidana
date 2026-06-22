<?php

namespace App\Filament\Resources\CustomPackageRequests\Pages;

use App\Filament\Resources\CustomPackageRequests\CustomPackageRequestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomPackageRequests extends ListRecords
{
    protected static string $resource = CustomPackageRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
