<?php

namespace App\Scraping;

use Symfony\Component\DomCrawler\Crawler;

class HepsiburadaScraper extends AbstractScraper
{
    public function getPrice(string $url, ?string $selector = null): array
    {
        $res = $this->http->get($url);
        $html = (string)$res->getBody();

        // 1) JSON-LD dene (Hepsiburada'da genelde var)
        if (preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/is', $html, $m)) {
            $json = json_decode(html_entity_decode($m[1]), true);
            if (isset($json['offers']['price'])) {
                return ['price' => (float)$json['offers']['price'], 'raw' => $json];
            }
        }

        // 2) Hepsiburada'ya özel fiyat elementleri
        $crawler = new Crawler($html);

        // Fiyat elementlerini dene
        $priceSelectors = [
            '.price-value',
            '.price-current',
            '.product-price',
            '[data-test-id="price-current-price"]',
            '.price-current-price',
            '.currentPrice',
        ];

        foreach ($priceSelectors as $priceSelector) {
            try {
                $priceElement = $crawler->filter($priceSelector)->first();
                if ($priceElement->count() > 0) {
                    $priceText = trim($priceElement->text());
                    $price = $this->normalize($priceText);
                    if ($price > 0) {
                        return ['price' => $price, 'raw' => ['selector' => $priceSelector, 'text' => $priceText]];
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // 3) CSS selector ile dene
        if ($selector) {
            try {
                $crawler = new Crawler($html);
                $txt = trim($crawler->filter($selector)->first()->text(''));
                return ['price' => $this->normalize($txt), 'raw' => ['selector' => $selector, 'text' => $txt]];
            } catch (\Exception $e) {
                // Selector çalışmadı, devam et
            }
        }

        throw new \RuntimeException('Hepsiburada\'da fiyat bulunamadı: ' . $url);
    }
}
