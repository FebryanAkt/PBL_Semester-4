<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\Icon;
use App\Filament\Widgets\BarangCategoryChart;

class DashboardAnalytics extends Page
{
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?string $title = 'Analytics';
    
    // gunakan enum Icon, bukan string
    
    // ini harus non-static
    protected string $view = 'filament.pages.dashboard-analytics';

    protected function getHeaderWidgets(): array
    {
        return [
            BarangCategoryChart::class,
            \App\Filament\Widgets\DashboardStats::class,
        ];
    }

    protected function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\BarangCategoryChart::class,
        ];
    }
}