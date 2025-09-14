<?php

namespace App\Scraping;

use App\Models\Marketplace;

class ScraperFactory
{
    public static function make(Marketplace $m): ScraperInterface
    {
        return match ($m->slug) {
            'teknosa' => new TeknosaScraper(),
            'hepsiburada' => new HepsiburadaScraper(),
            'trendyol' => new HepsiburadaScraper(), // Geçici olarak aynı scraper
            'n11' => new HepsiburadaScraper(), // Geçici olarak aynı scraper
            'gittigidiyor' => new HepsiburadaScraper(), // Geçici olarak aynı scraper
            default => new HepsiburadaScraper(), // Hepsiburada daha genel
        };
    }
}
