<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Subscriber;
use App\Notifications\PriceDropTelegram;
use Illuminate\Database\Seeder;

class NotificationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::with(['marketplace'])->first();

        if ($product) {
            echo "Test ürünü bulundu: " . $product->title . "\n";
            echo "Mevcut fiyat: " . $product->last_price . " TRY\n";

            // Test bildirimi gönder
            $subscribers = Subscriber::where('is_active', true)->get();

            if ($subscribers->count() > 0) {
                echo "Aktif abone sayısı: " . $subscribers->count() . "\n";

                foreach ($subscribers as $subscriber) {
                    echo "Abone: " . ($subscriber->name ?? 'İsimsiz') . " (Chat ID: " . $subscriber->chat_id . ")\n";

                    try {
                        // Test bildirimi gönder
                        $subscriber->notify(new PriceDropTelegram(
                            $product,
                            40000.00, // Yeni fiyat
                            45000.00, // Eski fiyat
                            11.11     // Düşüş yüzdesi
                        ));

                        echo "✅ Bildirim gönderildi!\n";
                    } catch (\Exception $e) {
                        echo "❌ Bildirim hatası: " . $e->getMessage() . "\n";
                    }
                }
            } else {
                echo "❌ Aktif abone bulunamadı!\n";
            }
        } else {
            echo "❌ Test ürünü bulunamadı!\n";
        }
    }
}
