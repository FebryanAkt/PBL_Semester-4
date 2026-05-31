<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\ChartWidget;

class ItemCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Item per Kategori';

    protected function getData(): array
    {
        $categories = Item::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Item',
                    'data' => $categories->values(),
                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                ],
            ],
            'labels' => $categories->keys(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}