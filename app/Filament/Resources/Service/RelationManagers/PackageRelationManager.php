<?php

namespace App\Filament\Resources\Service\RelationManagers;

use App\Filament\Resources\Package\PackageResource;
use Filament\Resources\RelationManagers\RelationManager;
use LaraZeus\SpatieTranslatable\Resources\RelationManagers\Concerns\Translatable;
use Livewire\Attributes\Reactive;

class PackageRelationManager extends RelationManager
{
    use Translatable;

    #[Reactive]
    public ?string $activeLocale = null;

    protected static string $relationship = 'packages';

    protected static ?string $relatedResource = PackageResource::class;
}
