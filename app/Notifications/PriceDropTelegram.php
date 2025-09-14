<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class PriceDropTelegram extends Notification
{
    use Queueable;

    public function __construct(
        protected Product $product,
        protected float $new,
        protected float $last,
        protected float $pct
    ) {}

    public function via($notifiable): array
    {
        return ['telegram'];
    }

    public function toTelegram($notifiable)
    {
        $p = $this->product;
        $msg = "🔔 *Fiyat indirimi tespit edildi!*\n" .
               "📦 {$p->title}\n" .
               "💸 Yeni: *" . number_format($this->new, 2) . "* {$p->currency}\n" .
               "🕘 Eski: " . number_format($this->last, 2) . " {$p->currency}\n" .
               "📉 İndirim: " . number_format($this->pct, 2) . "%\n" .
               "🔗 {$p->url}";

        return TelegramMessage::create()
            ->to($notifiable->chat_id)
            ->content($msg)
            ->options(['parse_mode' => 'Markdown']);
    }
}
