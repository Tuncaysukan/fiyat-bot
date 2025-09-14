<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Marketplace;
use App\Models\PriceRule;
use Illuminate\Console\Command;

class ImportProductsCommand extends Command
{
    protected $signature = 'products:import
                            {urls* : Ürün URL\'leri (virgülle ayrılmış)}
                            {--marketplace=hepsiburada : Marketplace slug}
                            {--drop-percent=5 : Fiyat düşüş yüzdesi}
                            {--drop-amount=100 : Fiyat düşüş tutarı}
                            {--batch-size=10 : Toplu işlem boyutu}';

    protected $description = 'Toplu ürün ekleme - URL\'leri otomatik olarak analiz eder ve ekler';

    public function handle()
    {
        $urls = $this->argument('urls');
        $marketplaceSlug = $this->option('marketplace');
        $dropPercent = (float) $this->option('drop-percent');
        $dropAmount = (float) $this->option('drop-amount');
        $batchSize = (int) $this->option('batch-size');

        $this->info("🚀 Toplu ürün ekleme başlatılıyor...");
        $this->info("Marketplace: {$marketplaceSlug}");
        $this->info("Düşüş kuralı: %{$dropPercent} veya {$dropAmount} TL");
        $this->info("Toplam URL: " . count($urls));

        $marketplace = Marketplace::where('slug', $marketplaceSlug)->first();
        if (!$marketplace) {
            $this->error("❌ Marketplace bulunamadı: {$marketplaceSlug}");
            return 1;
        }

        $successCount = 0;
        $errorCount = 0;
        $progressBar = $this->output->createProgressBar(count($urls));

        foreach (array_chunk($urls, $batchSize) as $batch) {
            foreach ($batch as $url) {
                try {
                    $this->addProduct($url, $marketplace, $dropPercent, $dropAmount);
                    $successCount++;
                } catch (\Exception $e) {
                    $this->error("\n❌ Hata: {$url} - " . $e->getMessage());
                    $errorCount++;
                }
                $progressBar->advance();
            }

            // Batch arası gecikme (siteyi yormamak için)
            if (count($batch) === $batchSize) {
                sleep(1);
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✅ Başarılı: {$successCount}");
        $this->error("❌ Hatalı: {$errorCount}");
        $this->info("📊 Toplam: " . count($urls));

        return 0;
    }

    private function addProduct(string $url, Marketplace $marketplace, float $dropPercent, float $dropAmount): void
    {
        // URL'den ürün bilgilerini çıkar
        $title = $this->extractTitleFromUrl($url);

        // Ürün zaten var mı kontrol et
        if (Product::where('url', $url)->exists()) {
            throw new \Exception('Ürün zaten mevcut');
        }

        // Ürün oluştur
        $product = Product::create([
            'marketplace_id' => $marketplace->id,
            'title' => $title,
            'url' => $url,
            'parse_strategy' => 'selector',
            'selector' => $this->getDefaultSelector($marketplace->slug),
            'currency' => 'TRY',
            'last_price' => null,
            'is_active' => true,
        ]);

        // Fiyat kuralı ekle
        PriceRule::create([
            'product_id' => $product->id,
            'drop_percent' => $dropPercent,
            'drop_amount' => $dropAmount,
        ]);
    }

    private function extractTitleFromUrl(string $url): string
    {
        // URL'den ürün adını çıkarmaya çalış
        $path = parse_url($url, PHP_URL_PATH);
        $segments = explode('/', trim($path, '/'));

        // Son segment'i al (genelde ürün adı)
        $lastSegment = end($segments);

        // Tireleri boşlukla değiştir ve temizle
        $title = str_replace('-', ' ', $lastSegment);
        $title = ucwords($title);

        // Çok uzunsa kısalt
        if (strlen($title) > 100) {
            $title = substr($title, 0, 97) . '...';
        }

        return $title ?: 'Ürün - ' . date('Y-m-d H:i:s');
    }

    private function getDefaultSelector(string $marketplaceSlug): string
    {
        return match ($marketplaceSlug) {
            'teknosa' => '.price-current',
            'hepsiburada' => '.price-value',
            'trendyol' => '.prc-dsc',
            'n11' => '.newPrice',
            'gittigidiyor' => '.price',
            default => '.price-current',
        };
    }
}
