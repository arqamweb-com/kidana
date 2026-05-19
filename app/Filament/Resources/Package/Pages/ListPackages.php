<?php

namespace App\Filament\Resources\Package\Pages;

use App\Filament\Resources\Package\PackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;
use Illuminate\Database\Eloquent\Builder;

class ListPackages extends ListRecords
{
    use Translatable;

    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->icon('heroicon-m-bars-4'),
            'active' => Tab::make()
                ->icon('heroicon-m-eye')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', true)),
            'inactive' => Tab::make()
                ->icon('heroicon-m-eye-slash')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('is_active', false)),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'active';
    }
}
