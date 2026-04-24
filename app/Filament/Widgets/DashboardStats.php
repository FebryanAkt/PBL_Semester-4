<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat; // untuk v3
use Filament\Widgets\StatsOverviewWidget\Card; // untuk v2 (deprecated)
use Filament\Widgets\Widget;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Users', \App\Models\User::count())
                ->description('Total registered users')
                ->color('success'),

            Stat::make('Barangs', \App\Models\Barang::count())
                ->description('Total items')
                ->color('info'),
        ];
    }
}