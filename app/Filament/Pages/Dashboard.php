<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\TravelOperationsOverview;
use Filament\Pages\Dashboard as PageDashboard;

class Dashboard extends PageDashboard
{
    public function getWidgets(): array
    {
        return [
            TravelOperationsOverview::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }
}
