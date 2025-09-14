<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Marketplace;
use App\Models\Subscriber;
use App\Models\PriceHistory;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $totalMarketplaces = Marketplace::count();
        $activeSubscribers = Subscriber::where('is_active', true)->count();
        $totalPriceChecks = PriceHistory::count();
        $todayPriceChecks = PriceHistory::whereDate('created_at', today())->count();

        return [
            Stat::make('Toplam Ürün', $totalProducts)
                ->description('Tüm ürünler')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary'),

            Stat::make('Aktif Ürünler', $activeProducts)
                ->description('Fiyat takibi yapılan ürünler')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success'),

            Stat::make('Marketplace\'ler', $totalMarketplaces)
                ->description('Desteklenen mağazalar')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('info'),

            Stat::make('Aktif Aboneler', $activeSubscribers)
                ->description('Telegram bildirim alan kullanıcılar')
                ->descriptionIcon('heroicon-m-users')
                ->color('warning'),

            Stat::make('Toplam Fiyat Kontrolü', $totalPriceChecks)
                ->description('Tüm zamanlar')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('gray'),

            Stat::make('Bugünkü Kontroller', $todayPriceChecks)
                ->description('Son 24 saat')
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),
        ];
    }
}
