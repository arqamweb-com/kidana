<?php

namespace App\Filament\Resources\User\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::getModel()::count())
                ->description('Total number of users')
                ->color('primary')

                ->openUrlInNewTab(true)
                ->chart(
                    [20, 30, 15, 40, 25, 50],
                ),

            Stat::make('Admin Users', User::getModel()::where('role', 'admin')->count())
                ->description('Number of admin users')
                ->color('success')

                ->openUrlInNewTab(true)
                ->chart(
                    [15, 25, 10, 35, 20, 45],
                ),
        ];
    }
}
