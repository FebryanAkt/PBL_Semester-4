<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', \App\Models\User::count())
                ->description('Total registered users')
                ->icon('heroicon-o-users'),

            Stat::make('Barangs', \App\Models\Barang::count())
                ->description('Total items')
                ->icon('heroicon-o-archive-box'),
        ];
    }
}