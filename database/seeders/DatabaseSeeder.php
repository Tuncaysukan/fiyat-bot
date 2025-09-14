<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin kullanıcısı oluştur
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@fiyatbot.com',
        ]);

        // Seeder'ları çalıştır
        $this->call([
            MarketplaceSeeder::class,
            SubscriberSeeder::class,
            ProductSeeder::class,
        ]);
    }
}
