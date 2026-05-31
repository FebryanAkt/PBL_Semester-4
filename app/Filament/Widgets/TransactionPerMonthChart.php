<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;

class TransactionPerMonthChart extends ChartWidget
{
    protected ?string $heading = 'Transaksi Per Bulan';

    protected function getData(): array
    {
        $transactions = Transaction::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Transaksi',
                    'data' => $transactions->values(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#6366f1', '#14b8a6'],
                ],
            ],
            'labels' => $transactions->keys()->map(function ($bulan) {
                return date('F', mktime(0, 0, 0, $bulan, 1)); // ubah angka bulan jadi nama bulan
            }),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; 
    }
}