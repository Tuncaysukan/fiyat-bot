<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Marketplace;
use Filament\Widgets\ChartWidget;

class MarketplaceDistributionWidget extends ChartWidget
{
    protected ?string $heading = 'Marketplace Dağılımı';

    protected function getData(): array
    {
        $marketplaces = Marketplace::withCount('products')->get();

        $labels = $marketplaces->pluck('name')->toArray();
        $data = $marketplaces->pluck('products_count')->toArray();

        // Renk paleti
        $colors = [
            'rgb(59, 130, 246)',   // Mavi
            'rgb(16, 185, 129)',   // Yeşil
            'rgb(245, 158, 11)',   // Sarı
            'rgb(239, 68, 68)',    // Kırmızı
            'rgb(139, 92, 246)',   // Mor
            'rgb(236, 72, 153)',   // Pembe
        ];

        return [
            'datasets' => [
                [
                    'label' => 'Ürün Sayısı',
                    'data' => $data,
                    'backgroundColor' => array_slice($colors, 0, count($data)),
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            return context.label + ': ' + context.parsed + ' ürün';
                        }"
                    ]
                ],
            ],
        ];
    }
}
