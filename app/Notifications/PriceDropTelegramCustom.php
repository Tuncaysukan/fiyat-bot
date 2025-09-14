<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PriceDropTelegramCustom extends Notification
{
    use Queueable;

    public function __construct(
        protected Product $product,
        protected float $newPrice,
        protected float $lastPrice,
        protected float $dropPercent,
        protected string $botToken
    ) {}

    public function via($notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        $product = $this->product;
        $botName = $notifiable->bot_name ?? 'Fiyat Bot';

        $message = "🔔 *{$botName} - Fiyat İndirimi!*\n\n" .
                   "📦 *{$product->title}*\n" .
                   "🏪 {$product->marketplace->name}\n\n" .
                   "💰 *Yeni Fiyat:* " . number_format($this->newPrice, 2) . " {$product->currency}\n" .
                   "📉 *Eski Fiyat:* " . number_format($this->lastPrice, 2) . " {$product->currency}\n" .
                   "📊 *İndirim:* " . number_format($this->dropPercent, 2) . "%\n\n" .
                   "🔗 [Ürünü Görüntüle]({$product->url})\n\n" .
                   "⏰ " . now()->format('d.m.Y H:i');

        return TelegramMessage::create()
            ->to($notifiable->chat_id)
            ->content($message)
            ->options([
                'parse_mode' => 'Markdown',
                'disable_web_page_preview' => false,
            ]);
    }

    public function routeNotificationForTelegram($notification)
    {
        return $this->botToken;
    }
}
