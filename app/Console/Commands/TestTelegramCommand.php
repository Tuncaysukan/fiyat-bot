<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Subscriber;
use App\Notifications\PriceDropTelegram;
use Illuminate\Console\Command;

class TestTelegramCommand extends Command
{
    protected $signature = 'telegram:test';
    protected $description = 'Telegram bildirim testi';

    public function handle()
    {
        $this->info('🔔 Telegram bildirim testi başlatılıyor...');

        // İlk ürünü al
        $product = Product::first();
        if (!$product) {
            $this->error('❌ Hiç ürün bulunamadı!');
            return;
        }

        $this->info("📦 Test ürünü: {$product->title}");

        // Aktif aboneleri al
        $subscribers = Subscriber::where('is_active', true)->get();
        if ($subscribers->isEmpty()) {
            $this->error('❌ Hiç aktif abone bulunamadı!');
            return;
        }

        $this->info("👥 Aktif abone sayısı: {$subscribers->count()}");

        // Test bildirimi gönder
        $newPrice = 35000.00;
        $oldPrice = 45000.00;
        $dropPercent = 22.22;

        $this->info("💰 Test fiyatları: {$oldPrice} → {$newPrice} ({$dropPercent}% düşüş)");

        foreach ($subscribers as $subscriber) {
            try {
                $subscriber->notify(new PriceDropTelegram($product, $newPrice, $oldPrice, $dropPercent));
                $this->info("✅ Bildirim gönderildi: {$subscriber->name} (Chat ID: {$subscriber->chat_id})");
            } catch (\Exception $e) {
                $this->error("❌ Hata: {$subscriber->name} - {$e->getMessage()}");
            }
        }

        $this->info('🎉 Test tamamlandı!');
    }
}
