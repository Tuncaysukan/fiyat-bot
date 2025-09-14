<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Subscriber;
use App\Notifications\PriceDropTelegram;
use Illuminate\Database\Seeder;

class RealTelegramTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "=== TELEGRAM BOT TEST ===\n";
        echo "Bu test gerçek bir Telegram chat ID'si gerektirir.\n";
        echo "Telegram'da @BotFather'dan bot oluşturun ve chat ID'nizi alın.\n\n";

        // Kullanıcıdan chat ID al
        echo "Telegram Chat ID'nizi girin (örn: 123456789): ";
        $chatId = trim(fgets(STDIN));

        if (empty($chatId)) {
            echo "❌ Chat ID boş olamaz!\n";
            return;
        }

        // Test abonesi oluştur
        $subscriber = Subscriber::updateOrCreate(
            ['chat_id' => $chatId],
            [
                'name' => 'Test Kullanıcı - Gerçek',
                'is_active' => true,
            ]
        );

        echo "✅ Test abonesi oluşturuldu: " . $subscriber->name . "\n";
        echo "Chat ID: " . $subscriber->chat_id . "\n\n";

        // Test ürünü al
        $product = Product::with(['marketplace'])->first();

        if ($product) {
            echo "Test ürünü: " . $product->title . "\n";
            echo "Mevcut fiyat: " . $product->last_price . " TRY\n\n";

            echo "Test bildirimi gönderiliyor...\n";

            try {
                $subscriber->notify(new PriceDropTelegram(
                    $product,
                    40000.00, // Yeni fiyat
                    45000.00, // Eski fiyat
                    11.11     // Düşüş yüzdesi
                ));

                echo "✅ Bildirim başarıyla gönderildi!\n";
                echo "Telegram'ınızı kontrol edin.\n";

            } catch (\Exception $e) {
                echo "❌ Bildirim hatası: " . $e->getMessage() . "\n";
                echo "Bot token'ınızı kontrol edin.\n";
            }
        } else {
            echo "❌ Test ürünü bulunamadı!\n";
        }
    }
}
