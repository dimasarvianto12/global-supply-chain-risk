<?php

namespace App\Services;

use App\Models\PositiveWord;
use App\Models\NegativeWord;

class SentimentAnalysisService
{
    public function analyze($text)
    {
        if (empty($text)) {
            return ['label' => 'Neutral', 'score' => 50, 'positive_count' => 0, 'negative_count' => 0];
        }

        // Tokenisasi: Ubah teks ke huruf kecil dan bersihkan tanda baca
        $cleanText = strtolower(preg_replace('/[^\w\s]/', '', $text));
        $words = explode(' ', $cleanText);

        // Ambil kamus dari database
        $positiveWords = PositiveWord::pluck('word')->toArray();
        $negativeWords = NegativeWord::pluck('word')->toArray();

        $positiveCount = 0;
        $negativeCount = 0;

        // Hitung kecocokan kata berdasarkan rumus halaman 7 & 8 di PDF
        foreach ($words as $word) {
            if (in_array($word, $positiveWords)) { $positiveCount++; }
            if (in_array($word, $negativeWords)) { $negativeCount++; }
        }

        // Penentuan Label Sentimen dan Nilai Konversi Risiko (0 - 100)
        if ($positiveCount > $negativeCount) {
            $label = 'Positive';
            $score = 20; // Risiko rendah jika berita positif
        } elseif ($negativeCount > $positiveCount) {
            $label = 'Negative';
            $score = 80; // Risiko tinggi jika berita negatif
        } else {
            $label = 'Neutral';
            $score = 50; // Risiko sedang jika netral
        }

        return [
            'label' => $label,
            'score' => $score,
            'positive_count' => $positiveCount,
            'negative_count' => $negativeCount
        ];
    }
}