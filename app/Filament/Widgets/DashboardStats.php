<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
//use Filament\Widgets\StatsOverviewWidget\Card;

class DashboardStats extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        return [
            //
            Stat::make('Total Barang', \App\Models\Barang::count()),
            Stat::make('Total Users', \App\Models\User::count()),
        ];
    }
}
