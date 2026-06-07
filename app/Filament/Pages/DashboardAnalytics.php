<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Items\ItemResource;
use App\Filament\Resources\Transactions\TransactionResource;
use App\Filament\Widgets\DashboardStats;
use Filament\Pages\Page;
use App\Filament\Widgets\ItemCategoryChart;
use App\Filament\Widgets\TransactionPerMonthChart;
use App\Models\Item;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class DashboardAnalytics extends Page
{
    protected static ?string $navigationLabel = 'Analitik';
    protected static ?string $title = 'Dasbor Analitik';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.dashboard-analytics';

    protected Width|string|null $maxContentWidth = Width::Full;

    public function getSubheading(): string|Htmlable|null
    {
        return 'Pantau performa marketplace, inventaris, dan transaksi Bekaswit dalam satu tampilan.';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DashboardStats::class,
            ItemCategoryChart::class,
            TransactionPerMonthChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 2;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('kelolaBarang')
                ->label('Kelola Barang')
                ->icon('heroicon-o-archive-box')
                ->color('primary')
                ->url(ItemResource::getUrl('index')),
            Action::make('lihatWebsite')
                ->label('Lihat Website')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(route('home'))
                ->openUrlInNewTab(),
        ];
    }

    protected function getViewData(): array
    {
        $itemStatusCounts = Item::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalItems = max((int) $itemStatusCounts->sum(), 1);

        return [
            'itemStatuses' => [
                [
                    'label' => 'Tersedia',
                    'value' => (int) ($itemStatusCounts['tersedia'] ?? 0),
                    'percentage' => ((int) ($itemStatusCounts['tersedia'] ?? 0) / $totalItems) * 100,
                    'color' => '#2E7D32',
                ],
                [
                    'label' => 'Dalam Pesanan',
                    'value' => (int) ($itemStatusCounts['booking'] ?? 0),
                    'percentage' => ((int) ($itemStatusCounts['booking'] ?? 0) / $totalItems) * 100,
                    'color' => '#F59E0B',
                ],
                [
                    'label' => 'Terjual',
                    'value' => (int) ($itemStatusCounts['terjual'] ?? 0),
                    'percentage' => ((int) ($itemStatusCounts['terjual'] ?? 0) / $totalItems) * 100,
                    'color' => '#64748B',
                ],
            ],
            'recentTransactions' => Transaction::query()
                ->with(['user', 'item'])
                ->latest()
                ->limit(5)
                ->get(),
            'transactionsUrl' => TransactionResource::getUrl('index'),
        ];
    }
}
