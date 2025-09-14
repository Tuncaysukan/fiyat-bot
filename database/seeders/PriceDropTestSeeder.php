<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\PriceHistory;
use App\Services\PriceChecker;
use Illuminate\Database\Seeder;

class PriceDropTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::with(['rule', 'marketplace'])->first();

        if ($product) {
            echo "Test ürünü bulundu: " . $product->title . "\n";
            echo "Mevcut fiyat: " . $product->last_price . " TRY\n";
            echo "Fiyat kuralı: %" . $product->rule->drop_percent . " veya " . $product->rule->drop_amount . " TRY\n";

            // Fiyat düşüşü simüle et (45000 -> 40000 = %11 düşüş)
            $newPrice = 40000.00;
            $oldPrice = $product->last_price;
            $drop = $oldPrice - $newPrice;
            $dropPercent = ($drop / $oldPrice) * 100;

            echo "Yeni fiyat: " . $newPrice . " TRY\n";
            echo "Düşüş: " . $drop . " TRY (%" . number_format($dropPercent, 2) . ")\n";

            // Fiyat geçmişine ekle
            PriceHistory::create([
                'product_id' => $product->id,
                'price' => $newPrice,
                'raw' => ['test' => 'price_drop_simulation'],
            ]);

            // Ürün fiyatını güncelle
            $product->update(['last_price' => $newPrice]);

            echo "Fiyat güncellendi!\n";

            // PriceChecker ile bildirim gönder
            $checker = new PriceChecker();
            $result = $checker->handle($product);

            if ($result) {
                echo "✅ Bildirim gönderildi!\n";
                echo "Yeni fiyat: " . $result['new'] . " TRY\n";
                echo "Eski fiyat: " . $result['last'] . " TRY\n";
                echo "Düşüş: %" . number_format($result['pct'], 2) . "\n";
            } else {
                echo "❌ Bildirim gönderilmedi (kural eşiği aşılmadı)\n";
            }
        } else {
            echo "❌ Test ürünü bulunamadı!\n";
        }
    }
}
