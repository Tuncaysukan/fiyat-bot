<?php
namespace App\Services;

use App\Models\Product;
use App\Models\PriceHistory;
use App\Models\Subscriber;
use App\Scraping\ScraperFactory;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PriceDropTelegram;
use App\Notifications\PriceDropTelegramCustom;

class PriceChecker {
    public function handle(Product $p): ?array {
        $scraper = ScraperFactory::make($p->marketplace);
        $data = $scraper->getPrice($p->url, $p->selector);
        $new = $data['price'];
        $last = $p->last_price ?? $new;
        $drop = $last - $new;
        $pct  = $last > 0 ? ($drop / $last * 100) : 0;

        // history anti-spam: son 24 saatte aynı fiyat varsa bildirme
        $already = PriceHistory::where('product_id',$p->id)
            ->where('price',$new)
            ->where('created_at','>=',now()->subDay())
            ->exists();

        // Eşiği çek
        $rule = $p->rule;
        $need = !$already && $rule && (
            ($rule->drop_percent > 0 && $pct >= (float)$rule->drop_percent) ||
            ($rule->drop_amount  > 0 && $drop >= (float)$rule->drop_amount)
        );

        // history + last_price
        PriceHistory::create(['product_id'=>$p->id,'price'=>$new,'raw'=>$data['raw'] ?? null]);
        $p->update(['last_price'=>$new]);

        if ($need) {
            $subscribers = Subscriber::where('is_active', true)->get();
            foreach ($subscribers as $subscriber) {
                // Eğer abonenin kendi bot token'ı varsa onu kullan
                if ($subscriber->bot_token) {
                    $subscriber->notify(new PriceDropTelegramCustom($p, $new, $last, $pct, $subscriber->bot_token));
                } else {
                    // Yoksa varsayılan bot'u kullan
                    $subscriber->notify(new PriceDropTelegram($p, $new, $last, $pct));
                }
            }
            return compact('new','last','pct');
        }
        return null;
    }
}
