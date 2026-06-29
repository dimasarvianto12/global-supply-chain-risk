<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ExternalApiService;
use App\Services\SentimentAnalysisService;
use App\Services\RiskScoringService;

class TestApiIntegration extends Command
{
    protected $signature = 'test:api';
    protected $description = 'Uji coba Integrasi API, Analisis Sentimen, dan Perhitungan Risiko Global';

    public function handle(
        ExternalApiService $apiService, 
        SentimentAnalysisService $sentimentService,
        RiskScoringService $riskService
    ) {
        $this->info("==============================================");
        $this->info("   MEMULAI RUNNING ENGINE ANALITIK PLATFORM   ");
        $this->info("==============================================");

        // 1. Ambil Contoh Kalimat Skenario Krisis Ekonomi di PDF Halaman 7
        $sampleNewsText = "Inflation increases while exports decrease due to war.";
        $this->info("\n[1] Menjalankan Engine Analisis Sentimen Teks Berita...");
        $sentimentResult = $sentimentService->analyze($sampleNewsText);
        
        $this->line("-> Isi Berita: \"" . $sampleNewsText . "\"");
        $this->line("-> Hasil Sentimen: " . $sentimentResult['label'] . " (Kata Positif: " . $sentimentResult['positive_count'] . ", Kata Negatif: " . $sentimentResult['negative_count'] . ")");

        // 2. Simulasi Data Indikator Risiko (Skor Skala 0 - 100)
        $this->info("\n[2] Menghitung Prediksi Risiko Rantai Pasok (Weighted Risk Model)...");
        
        $weatherRisk = 40;   // Contoh: Ada indikasi hujan ringan di pelabuhan (Skor 40)
        $inflationRisk = 70; // Contoh: Inflasi negara asal agak tinggi (Skor 70)
        $newsRisk = $sentimentResult['score']; // Diambil otomatis dari hasil sentimen berita tadi (Skor 80)
        $currencyRisk = 30;  // Contoh: Fluktuasi nilai mata uang stabil-rendah (Skor 30)

        // Jalankan kalkulator pembobotan matematika
        $totalRisk = $riskService->calculateTotalRisk($weatherRisk, $inflationRisk, $newsRisk, $currencyRisk);

        $this->line("-> Weather Risk Weight (30%): " . $weatherRisk);
        $this->line("-> Inflation Risk Weight (20%): " . $inflationRisk);
        $this->line("-> Political/News Risk Weight (40%): " . $newsRisk);
        $this->line("-> Currency Risk Weight (10%): " . $currencyRisk);
        $this->warn("\n>>> TOTAL SUPPLY CHAIN RISK SCORE: " . $totalRisk . "% <<<");

        $this->info("==============================================");
        $this->info("         PENGUJIAN ANALITIK SELESAI           ");
        $this->info("==============================================");
    }
}