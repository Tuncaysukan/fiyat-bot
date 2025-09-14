<?php

namespace Database\Seeders;

use App\Models\Marketplace;
use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marketplaces = [
            [
                'name' => 'Teknosa',
                'slug' => 'teknosa',
                'base_url' => 'https://www.teknosa.com',
            ],
            [
                'name' => 'Hepsiburada',
                'slug' => 'hepsiburada',
                'base_url' => 'https://www.hepsiburada.com',
            ],
            [
                'name' => 'Trendyol',
                'slug' => 'trendyol',
                'base_url' => 'https://www.trendyol.com',
            ],
            [
                'name' => 'N11',
                'slug' => 'n11',
                'base_url' => 'https://www.n11.com',
            ],
            [
                'name' => 'GittiGidiyor',
                'slug' => 'gittigidiyor',
                'base_url' => 'https://www.gittigidiyor.com',
            ],
        ];

        foreach ($marketplaces as $marketplace) {
            Marketplace::firstOrCreate(
                ['slug' => $marketplace['slug']],
                $marketplace
            );
        }
    }
}
