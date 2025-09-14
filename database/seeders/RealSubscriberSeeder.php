<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Illuminate\Database\Seeder;

class RealSubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gerçek Telegram chat ID'si ile abone oluştur
        $subscriber = Subscriber::updateOrCreate(
            ['chat_id' => '1261411341'],
            [
                'name' => 'Tncysss (Gerçek)',
                'is_active' => true,
            ]
        );

        echo "✅ Gerçek abone oluşturuldu: " . $subscriber->name . "\n";
        echo "Chat ID: " . $subscriber->chat_id . "\n";
        echo "Aktif: " . ($subscriber->is_active ? 'Evet' : 'Hayır') . "\n";
    }
}
