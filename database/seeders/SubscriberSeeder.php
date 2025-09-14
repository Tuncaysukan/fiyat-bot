<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscribers = [
            [
                'name' => 'Test Kullanıcı 1',
                'chat_id' => '123456789',
                'is_active' => true,
            ],
            [
                'name' => 'Test Kullanıcı 2',
                'chat_id' => '987654321',
                'is_active' => true,
            ],
        ];

        foreach ($subscribers as $subscriber) {
            Subscriber::firstOrCreate(
                ['chat_id' => $subscriber['chat_id']],
                $subscriber
            );
        }
    }
}
