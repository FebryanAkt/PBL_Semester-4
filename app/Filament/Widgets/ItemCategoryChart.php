<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class ItemCategoryChart extends ChartWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'Komposisi Barang';
    protected ?string $description = 'Sebaran barang berdasarkan kategori';
    protected ?string $maxHeight = '320px';
    protected bool $isCollapsible = true;

    protected function getData(): array
    {
        $categories = Category::query()
            ->withCount('items')
            ->orderByDesc('items_count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Barang',
                    'data' => $categories->pluck('items_count')->values(),
                    'backgroundColor' => ['#1F3A5F', '#2E7D32', '#F59E0B', '#38BDF8', '#8B5CF6', '#64748B'],
                    'borderColor' => '#ffffff',
                    'borderWidth' => 3,
                    'hoverOffset' => 8,
                ],
            ],
            'labels' => $categories->pluck('name')->values(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,
            'cutout' => '64%',
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 18,
                    ],
                ],
            ],
        ];
    }
}
