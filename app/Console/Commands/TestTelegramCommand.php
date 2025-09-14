<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Subscriber;
use App\Notifications\PriceDropTelegram;
use App\Notifications\PriceDropTelegramCustom;
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
                // Eğer abonenin kendi bot token'ı varsa onu kullan
                if ($subscriber->bot_token) {
                    $subscriber->notify(new PriceDropTelegramCustom($product, $newPrice, $oldPrice, $dropPercent, $subscriber->bot_token));
                    $this->info("✅ Özel bot ile bildirim gönderildi: {$subscriber->name} (Bot: {$subscriber->bot_name})");
                } else {
                    $subscriber->notify(new PriceDropTelegram($product, $newPrice, $oldPrice, $dropPercent));
                    $this->info("✅ Varsayılan bot ile bildirim gönderildi: {$subscriber->name}");
                }
            } catch (\Exception $e) {
                $this->error("❌ Hata: {$subscriber->name} - {$e->getMessage()}");
            }
        }

        $this->info('🎉 Test tamamlandı!');
    }
}
