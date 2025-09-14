<?php
namespace App\Scraping;

use Symfony\Component\DomCrawler\Crawler;

class TeknosaScraper extends AbstractScraper {
    public function getPrice(string $url, ?string $selector = null): array {
        $res = $this->http->get($url);
        $html = (string)$res->getBody();

        // 1) ld+json dene
        if (preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/is', $html, $m)) {
            $json = json_decode(html_entity_decode($m[1]), true);
            if (isset($json['offers']['price'])) {
                return ['price' => (float)$json['offers']['price'], 'raw' => $json];
            }
        }
        // 2) CSS selector
        if ($selector) {
            $crawler = new Crawler($html);
            $txt = trim($crawler->filter($selector)->first()->text(''));
            return ['price' => $this->normalize($txt), 'raw' => ['selector' => $selector, 'text' => $txt]];
        }
        throw new \RuntimeException('Price not found');
    }
}
