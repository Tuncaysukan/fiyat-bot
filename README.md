# 🛒 Fiyat Bot - Otomatik Fiyat Takip Sistemi

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-red.svg" alt="Laravel 11">
  <img src="https://img.shields.io/badge/Filament-4.0-blue.svg" alt="Filament 4.0">
  <img src="https://img.shields.io/badge/PHP-8.1+-green.svg" alt="PHP 8.1+">
  <img src="https://img.shields.io/badge/Telegram-Bot-blue.svg" alt="Telegram Bot">
</p>

## 📋 Proje Hakkında

**Fiyat Bot**, e-ticaret sitelerindeki ürün fiyatlarını otomatik olarak takip eden ve fiyat düşüşlerinde Telegram bildirimi gönderen gelişmiş bir Laravel uygulamasıdır.

### 🎯 Temel Özellikler

- **🔄 Otomatik Fiyat Tarama**: Her 10 dakikada bir ürün fiyatlarını kontrol eder
- **📱 Telegram Bildirimleri**: Fiyat düşüşlerinde anında bildirim gönderir
- **🏪 Çoklu Marketplace Desteği**: Teknosa, Hepsiburada, Trendyol, N11, GittiGidiyor
- **⚙️ Esnek Fiyat Kuralları**: Yüzde veya tutar bazlı düşüş eşikleri
- **📊 Detaylı Raporlama**: Fiyat geçmişi ve istatistikler
- **👥 Çoklu Abone Sistemi**: Birden fazla kullanıcıya bildirim gönderme
- **🚀 Toplu Ürün Ekleme**: CSV/URL listesi ile binlerce ürün ekleme

## 🛠️ Teknoloji Stack

- **Backend**: Laravel 11, PHP 8.1+
- **Admin Panel**: Filament 4.0
- **Frontend**: Tailwind CSS 4.0, Alpine.js
- **Database**: PostgreSQL
- **Queue System**: Laravel Queue (Database Driver)
- **Scraping**: GuzzleHttp, Symfony DomCrawler
- **Notifications**: Telegram Bot API
- **Scheduling**: Laravel Scheduler

## 📦 Kurulum

### Gereksinimler
- PHP 8.1+
- Composer
- PostgreSQL
- Node.js & NPM

### Adım 1: Projeyi Klonlayın
```bash
git clone https://github.com/yourusername/fiyat-bot.git
cd fiyat-bot
```

### Adım 2: Bağımlılıkları Yükleyin
```bash
composer install
npm install
```

### Adım 3: Ortam Değişkenlerini Ayarlayın
```bash
cp .env.example .env
php artisan key:generate
```

`.env` dosyasını düzenleyin:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=Yapayzeka
DB_USERNAME=your_username
DB_PASSWORD=your_password

TELEGRAM_BOT_TOKEN=your_telegram_bot_token
```

### Adım 4: Veritabanını Hazırlayın
```bash
php artisan migrate
php artisan db:seed
```

### Adım 5: Assets'leri Derleyin
```bash
npm run build
```

## 🚀 Kullanım

### Admin Panel Erişimi
- **URL**: `http://localhost:8000/admin`
- **Email**: `admin@fiyatbot.com`
- **Şifre**: `password`

### Temel İşlemler

#### 1. Marketplace Ekleme
Admin panel → Marketplaces → Create
- Teknosa, Hepsiburada, Trendyol, N11, GittiGidiyor

#### 2. Ürün Ekleme
**Manuel:**
Admin panel → Products → Create

**Toplu:**
```bash
php artisan products:import "https://example.com/product1" "https://example.com/product2" --marketplace=hepsiburada --drop-percent=5
```

#### 3. Fiyat Kuralı Belirleme
Admin panel → Price Rules → Create
- Yüzde düşüş eşiği (örn: %5)
- Tutar düşüş eşiği (örn: 1000 TL)

#### 4. Abone Ekleme
Admin panel → Subscribers → Create
- Telegram Chat ID gerekli

### Otomatik Sistem

#### Queue Worker Başlatma
```bash
php artisan queue:work --queue=pricing --timeout=300
```

#### Scheduler Başlatma
```bash
php artisan schedule:work
```

## 📱 Telegram Bot Kurulumu

### 1. Bot Oluşturma
1. Telegram'da [@BotFather](https://t.me/botfather) ile konuşun
2. `/newbot` komutunu gönderin
3. Bot adı ve kullanıcı adı belirleyin
4. Bot token'ını alın

### 2. Bot Token'ını Ayarlama
`.env` dosyasına ekleyin:
```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
```

### 3. Chat ID Alma
1. Bot'unuzla konuşun
2. `/start` komutunu gönderin
3. Admin panel'den abone eklerken Chat ID'yi kullanın

## 🔧 Komutlar

### Fiyat Tarama
```bash
# Tüm ürünleri tara
php artisan price:scan

# Belirli ürünü tara
php artisan tinker
>>> App\Jobs\ScanProductPrice::dispatch(1);
```

### Toplu Ürün Ekleme
```bash
php artisan products:import "url1" "url2" --marketplace=hepsiburada --drop-percent=5 --drop-amount=100
```

### Telegram Test
```bash
php artisan telegram:test
```

## 📊 Dashboard Özellikleri

- **📈 İstatistikler**: Toplam ürün, aktif ürün, marketplace sayıları
- **📉 Fiyat Grafikleri**: Son 7 günlük fiyat kontrolü
- **🔔 Son Düşüşler**: En son fiyat düşüşleri
- **🏪 Marketplace Dağılımı**: Ürün dağılım grafikleri
- **⚡ Aktif Ürünler**: Şu anda takip edilen ürünler

## 🏗️ Proje Yapısı

```
app/
├── Console/Commands/          # Artisan komutları
├── Filament/Resources/        # Admin panel kaynakları
├── Jobs/                      # Queue job'ları
├── Models/                    # Eloquent modelleri
├── Notifications/             # Telegram bildirimleri
├── Scraping/                  # Web scraping sınıfları
└── Services/                  # İş mantığı servisleri

database/
├── migrations/                # Veritabanı migrasyonları
└── seeders/                   # Test verileri
```

## 🔒 Güvenlik

- **CSRF Koruması**: Tüm formlarda aktif
- **Rate Limiting**: API istekleri için sınırlama
- **Input Validation**: Tüm girişler doğrulanır
- **SQL Injection**: Eloquent ORM ile korunma
- **XSS Koruması**: Blade template güvenliği

## 🐛 Hata Ayıklama

### Queue Hataları
```bash
# Failed job'ları görüntüle
php artisan queue:failed

# Failed job'ı tekrar dene
php artisan queue:retry {job-id}
```

### Log Kontrolü
```bash
tail -f storage/logs/laravel.log
```

### Cache Temizleme
```bash
php artisan optimize:clear
```

## 📈 Performans

- **Queue System**: Asenkron işlemler
- **Database Indexing**: Hızlı sorgular
- **Caching**: Redis/Memcached desteği
- **Rate Limiting**: API istek sınırlaması
- **Background Jobs**: UI bloklaması yok

## 🤝 Katkıda Bulunma

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit yapın (`git commit -m 'Add amazing feature'`)
4. Push yapın (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## 📄 Lisans

Bu proje [MIT lisansı](https://opensource.org/licenses/MIT) altında lisanslanmıştır.

## 📞 İletişim

- **Proje Sahibi**: [Your Name]
- **Email**: your.email@example.com
- **GitHub**: [@yourusername](https://github.com/yourusername)

## 🙏 Teşekkürler

- [Laravel](https://laravel.com) - Web framework
- [Filament](https://filamentphp.com) - Admin panel
- [Telegram Bot API](https://core.telegram.org/bots/api) - Bildirim sistemi
- [Symfony DomCrawler](https://symfony.com/doc/current/components/dom_crawler.html) - Web scraping

---

<p align="center">
  <strong>⭐ Bu projeyi beğendiyseniz yıldız vermeyi unutmayın!</strong>
</p>