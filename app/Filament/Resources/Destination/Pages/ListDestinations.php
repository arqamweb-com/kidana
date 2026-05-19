<?php

namespace App\Filament\Resources\Destination\Pages;

use App\Filament\Resources\Destination\DestinationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListDestinations extends ListRecords
{
    use Translatable;

    protected static string $resource = DestinationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            CreateAction::make(),
        ];
    }
}
