<?php
namespace App\Scraping;

interface ScraperInterface {
    public function getPrice(string $url, ?string $selector = null): array;
    // return ['price' => 134999.90, 'raw' => ['...']]
}
