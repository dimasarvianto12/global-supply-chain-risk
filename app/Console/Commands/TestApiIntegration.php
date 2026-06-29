<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExternalApiService;

class TestApiIntegration extends Command
{
    protected $signature = 'test:api';
    protected $description = 'Uji coba seluruh integrasi API Platform Rantai Pasok';

    public function handle(ExternalApiService $apiService)
    {
        $this->info("=== MEMULAI PENGUJIAN MULTI-API ===");

        // 1. Uji REST Countries
        $this->info("\n1. Mengambil Profil Negara ID via REST Countries...");
        $country = $apiService->getRestCountriesData('ID');
        $this->line("Nama Resmi: " . ($country['name']['official'] ?? 'Gagal'));
        $this->line("Region: " . ($country['region'] ?? 'Gagal'));

        // 2. Uji ExchangeRate
        $this->info("\n2. Mengambil Kurs USD ke IDR...");
        $rates = $apiService->getExchangeRates('USD');
        $this->line("1 USD = " . ($rates['rates']['IDR'] ?? 'Gagal') . " IDR");

        // 3. Uji GNews (Berita)
        $this->info("\n3. Mengambil Berita Logistik/Ekonomi...");
        $news = $apiService->getNewsData('supply chain ID');
        $this->line("Judul Berita Terbaru: " . ($news['articles'][0]['title'] ?? 'Gagal'));

        $this->info("\n=== PENGUJIAN SELESAI ===");
    }
}