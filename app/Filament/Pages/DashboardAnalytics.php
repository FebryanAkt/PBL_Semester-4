<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\Icon;
use App\Filament\Widgets\ItemCategoryChart;
use App\Filament\Widgets\TransactionPerMonthChart;

class DashboardAnalytics extends Page
{
    protected static ?string $navigationLabel = 'Analitik';
    protected static ?string $title = 'Dasbor Analitik';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    // gunakan enum Icon, bukan string
    
    // ini harus non-static
    protected string $view = 'filament.pages.dashboard-analytics';
    

    protected function getHeaderWidgets(): array
    {
        return [
            ItemCategoryChart::class,
            \App\Filament\Widgets\TransactionPerMonthChart::class,
            \App\Filament\Widgets\DashboardStats::class,
        ];
    }

    protected function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ItemCategoryChart::class,
            \App\Filament\Widgets\TransactionPerMonthChart::class,
        ];
    }
}