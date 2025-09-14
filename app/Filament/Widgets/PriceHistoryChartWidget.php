<?php

namespace App\Filament\Widgets;

use App\Models\PriceHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class PriceHistoryChartWidget extends ChartWidget
{
    protected ?string $heading = 'Son 7 Günlük Fiyat Kontrolü';

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        // Son 7 günün verilerini al
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d.m');

            $count = PriceHistory::whereDate('created_at', $date->format('Y-m-d'))->count();
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Fiyat Kontrolü Sayısı',
                    'data' => $data,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
