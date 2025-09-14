<?php
namespace App\Scraping;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractScraper implements ScraperInterface {
    protected Client $http;
    public function __construct() {
        $this->http = new Client([
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124 Safari/537.36',
                'Accept-Language' => 'tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
            ],
        ]);
    }
    protected function normalize(string $txt): float {
        $t = preg_replace('/[^\d,\.]/', '', $txt);
        $t = str_replace(['.', ','], ['', '.'], $t); // "136.559,00" -> "136559.00"
        return (float)$t;
    }
}
