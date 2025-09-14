<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Subscriber;
use App\Notifications\PriceDropTelegram;
use Illuminate\Database\Seeder;

class RealTelegramNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "=== GERÇEK TELEGRAM BİLDİRİMİ TEST ===\n";

        // Gerçek aboneyi bul
        $subscriber = Subscriber::where('chat_id', '1261411341')->first();

        if (!$subscriber) {
            echo "❌ Gerçek abone bulunamadı!\n";
            return;
        }

        echo "✅ Abone bulundu: " . $subscriber->name . "\n";
        echo "Chat ID: " . $subscriber->chat_id . "\n\n";

        // Test ürünü al
        $product = Product::with(['marketplace'])->first();

        if (!$product) {
            echo "❌ Test ürünü bulunamadı!\n";
            return;
        }

        echo "Test ürünü: " . $product->title . "\n";
        echo "Marketplace: " . $product->marketplace->name . "\n";
        echo "Mevcut fiyat: " . $product->last_price . " TRY\n\n";

        echo "🚀 Gerçek Telegram bildirimi gönderiliyor...\n";

        try {
            $subscriber->notify(new PriceDropTelegram(
                $product,
                40000.00, // Yeni fiyat
                45000.00, // Eski fiyat
                11.11     // Düşüş yüzdesi
            ));

            echo "✅ Bildirim başarıyla gönderildi!\n";
            echo "📱 Telegram'ınızı kontrol edin!\n";
            echo "Bot: @firsat_12_bot\n";

        } catch (\Exception $e) {
            echo "❌ Bildirim hatası: " . $e->getMessage() . "\n";
            echo "Bot token'ınızı kontrol edin: 8313548329:AAF2ZtMXYBCQqDKlgzMCMsyocZZLEJSFTsw\n";
        }
    }
}
