<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Item; 

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pengguna', User::count())
                ->description('Total pengguna')
                ->icon('heroicon-o-users'),

            Stat::make('Barang', Item::count()) 
                ->description('Total barang')
                ->icon('heroicon-o-archive-box'),
        ];
    }
}