<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;

class DashboardStats extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'Ringkasan Utama';
    protected ?string $description = 'Kondisi marketplace Bekaswit saat ini';

    protected function getStats(): array
    {
        $userTrend = $this->dailyTrend(User::class);
        $itemTrend = $this->dailyTrend(Item::class);
        $transactionTrend = $this->dailyTrend(Transaction::class);
        $successfulTransactions = Transaction::where('status', 'success');
        $revenueTrend = $this->dailyRevenueTrend();

        return [
            Stat::make('Total Pengguna', number_format(User::count(), 0, ',', '.'))
                ->description(User::where('role', 'seller')->count() . ' akun penjual')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary')
                ->chart($userTrend)
                ->icon('heroicon-o-user-group'),

            Stat::make('Barang Aktif', number_format(Item::where('status', 'tersedia')->count(), 0, ',', '.'))
                ->description(Item::where('status', 'terjual')->count() . ' barang telah terjual')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart($itemTrend)
                ->icon('heroicon-o-archive-box'),

            Stat::make('Total Transaksi', number_format(Transaction::count(), 0, ',', '.'))
                ->description($successfulTransactions->count() . ' transaksi berhasil')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('warning')
                ->chart($transactionTrend)
                ->icon('heroicon-o-receipt-percent'),

            Stat::make(
                'Nilai Transaksi',
                'Rp ' . number_format((float) $successfulTransactions->sum('price'), 0, ',', '.')
            )
                ->description('Akumulasi transaksi berhasil')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info')
                ->chart($revenueTrend)
                ->icon('heroicon-o-banknotes'),
        ];
    }

    protected function getColumns(): int|array|null
    {
        return [
            'default' => 1,
            'sm' => 2,
            'xl' => 4,
        ];
    }

    private function dailyTrend(string $model): array
    {
        $counts = $model::query()
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get(['created_at'])
            ->countBy(fn ($record) => $record->created_at->format('Y-m-d'));

        return collect(range(6, 1))
            ->map(fn (int $daysAgo) => (float) ($counts[now()->subDays($daysAgo)->format('Y-m-d')] ?? 0))
            ->push((float) ($counts[now()->format('Y-m-d')] ?? 0))
            ->all();
    }

    private function dailyRevenueTrend(): array
    {
        $revenue = Transaction::query()
            ->where('status', 'success')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->get(['created_at', 'price'])
            ->groupBy(fn (Transaction $transaction) => $transaction->created_at->format('Y-m-d'))
            ->map(fn ($transactions) => (float) $transactions->sum('price'));

        return collect(range(6, 1))
            ->map(fn (int $daysAgo) => (float) ($revenue[now()->subDays($daysAgo)->format('Y-m-d')] ?? 0))
            ->push((float) ($revenue[now()->format('Y-m-d')] ?? 0))
            ->all();
    }
}
