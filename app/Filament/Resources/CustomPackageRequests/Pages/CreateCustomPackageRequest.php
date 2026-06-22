<?php

namespace App\Filament\Resources\CustomPackageRequests\Pages;

use App\Filament\Resources\CustomPackageRequests\CustomPackageRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomPackageRequest extends CreateRecord
{
    protected static string $resource = CustomPackageRequestResource::class;
}
