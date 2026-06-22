<?php

namespace App\Filament\Resources\CustomPackageRequests\Pages;

use App\Filament\Resources\CustomPackageRequests\CustomPackageRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomPackageRequest extends EditRecord
{
    protected static string $resource = CustomPackageRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
