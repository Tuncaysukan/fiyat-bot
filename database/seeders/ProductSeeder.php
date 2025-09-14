<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Marketplace;
use App\Models\PriceRule;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teknosa = Marketplace::where('slug', 'teknosa')->first();

        if ($teknosa) {
            $product = Product::create([
                'marketplace_id' => $teknosa->id,
                'title' => 'Test Ürün - iPhone 15 Pro 128GB',
                'url' => 'https://www.teknosa.com/iphone-15-pro-128gb-natural-titanium-p-125043001',
                'parse_strategy' => 'selector',
                'selector' => '.price-current',
                'currency' => 'TRY',
                'last_price' => 45000.00,
                'is_active' => true,
            ]);

            // Fiyat kuralı ekle
            PriceRule::create([
                'product_id' => $product->id,
                'drop_percent' => 5.00, // %5 düşüş
                'drop_amount' => 1000.00, // 1000 TL düşüş
            ]);

            // Fiyat geçmişi ekle
            $product->history()->create([
                'price' => 45000.00,
                'raw' => ['test' => 'initial_price'],
            ]);
        }
    }
}
