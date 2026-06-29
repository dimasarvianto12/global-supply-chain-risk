<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ExternalApiService
{
    /**
     * 1. World Bank API (GDP, Inflasi, Populasi)
     */
    public function getWorldBankData($countryCode, $indicator)
    {
        $url = "https://api.worldbank.org/v2/country/{$countryCode}/indicator/{$indicator}?format=json&per_page=5";

        try {
            $response = Http::timeout(10)->withoutVerifying()->get($url);
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data[1]) && is_array($data[1])) {
                    foreach ($data[1] as $record) {
                        if (isset($record['value']) && !is_null($record['value'])) {
                            return $record['value'];
                        }
                    }
                }
            }
            return null;
        } catch (\Exception $e) {
            \Log::error("World Bank API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 2. Open-Meteo API (Cuaca Real-time)
     */
    public function getWeatherData($latitude, $longitude)
    {
        $url = "https://api.open-meteo.com/v1/forecast";

        try {
            $response = Http::timeout(10)->withoutVerifying()->get($url, [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'current_weather' => true,
                'hourly' => 'rain,windspeed_10m'
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            \Log::error("Open-Meteo API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 3. REST Countries API (Dengan Proteksi Anti-Gagal / Mock Fallback)
     */
    public function getRestCountriesData($countryCode)
    {
        $url = "https://restcountries.com/v3.1/alpha/{$countryCode}";

        try {
            // Membatasi timeout ke 5 detik agar tidak membuat aplikasi loading terlalu lama
            $response = Http::timeout(5)->withoutVerifying()->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                $result = isset($data[0]) ? $data[0] : $data;
                
                // 🔍 VALIDASI EKSTRA: Pastikan struktur 'name' dan 'region' benar-benar ada
                if (isset($result['name']['official']) && isset($result['region'])) {
                    return $result; // Data valid, gunakan data live dari API
                }
            }
            
            // Jika respons sukses tapi struktur data kosong/aneh, lempar ke fallback
            return $this->getCountryFallbackMock($countryCode);
        } catch (\Exception $e) {
            \Log::error("REST Countries API Error: " . $e->getMessage());
            // Jika koneksi terputus atau timeout, gunakan data cadangan
            return $this->getCountryFallbackMock($countryCode);
        }
    }

    /**
     * 4. ExchangeRate API (Kurs Mata Uang Real-time)
     */
    public function getExchangeRates($baseCurrency = 'USD')
    {
        $url = "https://open.er-api.com/v6/latest/{$baseCurrency}";

        try {
            $response = Http::timeout(10)->withoutVerifying()->get($url);
            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            \Log::error("ExchangeRate API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 5. GNews API (Berita Logistik & Ekonomi Global)
     */
    public function getNewsData($keyword)
    {
        $apiKey = env('GNEWS_API_KEY');

        if (empty($apiKey) || $apiKey === 'isi_dengan_api_key_kamu_nanti') {
            return [
                'articles' => [
                    [
                        'title' => "Logistics Update: Supply Chain Crisis Affects Trade Routes",
                        'description' => "Inflation increases while exports decrease due to war and port delays.",
                        'url' => "https://example.com/news-mock",
                        'source' => ['name' => "Mock Logistics Intelligence"],
                        'publishedAt' => now()->toIso8601String()
                    ]
                ]
            ];
        }

        $url = "https://gnews.io/api/v4/search";
        try {
            $response = Http::timeout(10)->withoutVerifying()->get($url, [
                'q' => $keyword,
                'lang' => 'en',
                'apikey' => $apiKey
            ]);

            if ($response->successful()) {
                return $response->json();
            }
            return null;
        } catch (\Exception $e) {
            \Log::error("GNews API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Data Cadangan Internal untuk Negara Studi Kasus UAS (Aman & Selalu Siap)
     */
    private function getCountryFallbackMock($countryCode)
    {
        $mocks = [
            'ID' => [
                'name' => ['official' => 'Republic of Indonesia', 'common' => 'Indonesia'],
                'region' => 'Asia',
                'currencies' => ['IDR' => ['name' => 'Indonesian Rupiah', 'symbol' => 'Rp']],
                'languages' => ['ind' => 'Indonesian']
            ],
            'DE' => [
                'name' => ['official' => 'Federal Republic of Germany', 'common' => 'Germany'],
                'region' => 'Europe',
                'currencies' => ['EUR' => ['name' => 'Euro', 'symbol' => '€']],
                'languages' => ['deu' => 'German']
            ],
            'CN' => [
                'name' => ['official' => "People's Republic of China", 'common' => 'China'],
                'region' => 'Asia',
                'currencies' => ['CNY' => ['name' => 'Renminbi', 'symbol' => '¥']],
                'languages' => ['zho' => 'Chinese']
            ],
            'AU' => [
                'name' => ['official' => 'Commonwealth of Australia', 'common' => 'Australia'],
                'region' => 'Oceania',
                'currencies' => ['AUD' => ['name' => 'Australian Dollar', 'symbol' => '$']],
                'languages' => ['eng' => 'English']
            ]
        ];

        return $mocks[strtoupper($countryCode)] ?? null;
    }
}