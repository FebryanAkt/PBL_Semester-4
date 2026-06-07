<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class TransactionPerMonthChart extends ChartWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'Tren Transaksi';
    protected ?string $description = 'Pergerakan transaksi dalam 12 bulan terakhir';
    protected ?string $maxHeight = '320px';
    protected bool $isCollapsible = true;

    protected function getData(): array
    {
        $startMonth = now()->startOfMonth()->subMonths(11);
        $transactions = Transaction::query()
            ->where('created_at', '>=', $startMonth)
            ->get(['created_at'])
            ->countBy(fn (Transaction $transaction) => $transaction->created_at->format('Y-m'));

        $months = collect(range(0, 11))
            ->map(fn (int $offset) => $startMonth->copy()->addMonths($offset));

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $months
                        ->map(fn ($month) => (int) ($transactions[$month->format('Y-m')] ?? 0))
                        ->values(),
                    'borderColor' => '#2E7D32',
                    'backgroundColor' => 'rgba(46, 125, 50, 0.12)',
                    'pointBackgroundColor' => '#1F3A5F',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 2,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months
                ->map(fn ($month) => $month->translatedFormat('M y'))
                ->values(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                    'grid' => [
                        'color' => 'rgba(148, 163, 184, 0.18)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
