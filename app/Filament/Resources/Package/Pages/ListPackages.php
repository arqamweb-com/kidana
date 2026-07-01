<?php

namespace App\Filament\Resources\Package\Pages;

use App\Filament\Imports\PackageImporter;
use App\Filament\Resources\Package\PackageResource;
use App\Services\PackageCsvExporter;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListPackages extends ListRecords
{
    use Translatable;

    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
            ImportAction::make()
                ->importer(PackageImporter::class)
                ->csvDelimiter(',')
                ->options(fn (): array => ['locale' => $this->activeLocale]),
            Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn (): StreamedResponse => app(PackageCsvExporter::class)->download(
                    $this->getFilteredTableQuery(),
                    'packages-'.now()->format('Y-m-d_His').'.csv',
                )),
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->icon('heroicon-m-bars-4'),
            'active' => Tab::make()
                ->icon('heroicon-m-eye')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', true)),
            'inactive' => Tab::make()
                ->icon('heroicon-m-eye-slash')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_active', false)),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'active';
    }
}
