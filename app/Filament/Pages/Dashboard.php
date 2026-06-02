<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BusinessOverviewWidget;
use App\Filament\Widgets\ContentReadinessWidget;
use Filament\Pages\Dashboard as PageDashboard;

class Dashboard extends PageDashboard
{
    public function getWidgets(): array
    {
        return [
            BusinessOverviewWidget::class,
            ContentReadinessWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 1;
    }
}
