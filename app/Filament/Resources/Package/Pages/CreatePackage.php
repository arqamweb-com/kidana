<?php

namespace App\Filament\Resources\Package\Pages;

use App\Filament\Resources\Package\PackageResource;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreatePackage extends CreateRecord
{
    use Translatable;

    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }
}
