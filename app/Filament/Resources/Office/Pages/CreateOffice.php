<?php

namespace App\Filament\Resources\Office\Pages;

use App\Filament\Resources\Office\OfficeResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateOffice extends CreateRecord
{
    use Translatable;

    protected static string $resource = OfficeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
