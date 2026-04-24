<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use Filament\Widgets\ChartWidget;

class BarangCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Barang per Kategori';

    protected function getData(): array
    {
        $categories = Barang::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang',
                    'data' => $categories->values(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                ],
            ],
            'labels' => $categories->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar'; // atau 'line' kalau mau tren waktu
    }
}