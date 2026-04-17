<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\Icon;

class DashboardAnalytics extends Page
{
    protected static ?string $navigationLabel = 'Analytics';
    protected static ?string $title = 'Dashboard Analytics';

    // gunakan enum Icon, bukan string

    // ini harus non-static
    protected string $view = 'filament.pages.dashboard-analytics';
}