<?php

namespace App\Services;

class RiskScoringService
{
    /**
     * Menghitung Total Risiko menggunakan Weighted Risk Model sesuai spesifikasi UAS
     */
    public function calculateTotalRisk($weatherRisk, $inflationRisk, $newsRisk, $currencyRisk)
    {
        // Definisi Bobot Berdasarkan PDF Halaman 8
        $wWeather = 0.30;
        $wInflation = 0.20;
        $wNews = 0.40;
        $wCurrency = 0.10;

        // Rumus Algoritma Pembobotan: (Skor * Bobot)
        $totalRiskScore = ($weatherRisk * $wWeather) + 
                          ($inflationRisk * $wInflation) + 
                          ($newsRisk * $wNews) + 
                          ($currencyRisk * $wCurrency);

        return round($totalRiskScore, 2);
    }
}