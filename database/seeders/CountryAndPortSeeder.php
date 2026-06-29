<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Port;

class CountryAndPortSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Dataset Negara Studi Kasus UAS
        $countries = [
            'ID' => [
                'name' => 'Indonesia', 'official_name' => 'Republic of Indonesia',
                'region' => 'Asia', 'latitude' => -6.2088, 'longitude' => 106.8456, 'currency_code' => 'IDR',
                'ports' => [
                    ['name' => 'Port of Tanjung Priok (Jakarta)', 'lat' => -6.1014, 'lng' => 106.8824],
                    ['name' => 'Port of Tanjung Perak (Surabaya)', 'lat' => -7.2024, 'lng' => 112.7275],
                ]
            ],
            'DE' => [
                'name' => 'Germany', 'official_name' => 'Federal Republic of Germany',
                'region' => 'Europe', 'latitude' => 52.5200, 'longitude' => 13.4050, 'currency_code' => 'EUR',
                'ports' => [
                    ['name' => 'Port of Hamburg', 'lat' => 53.5413, 'lng' => 9.9850],
                    ['name' => 'Port of Bremen', 'lat' => 53.1225, 'lng' => 8.7093],
                ]
            ],
            'CN' => [
                'name' => 'China', 'official_name' => "People's Republic of China",
                'region' => 'Asia', 'latitude' => 35.8617, 'longitude' => 104.1954, 'currency_code' => 'CNY',
                'ports' => [
                    ['name' => 'Port of Shanghai', 'lat' => 31.2222, 'lng' => 121.5407],
                    ['name' => 'Port of Shenzhen', 'lat' => 22.5113, 'lng' => 113.9142],
                ]
            ],
            'AU' => [
                'name' => 'Australia', 'official_name' => 'Commonwealth of Australia',
                'region' => 'Oceania', 'latitude' => -25.2744, 'longitude' => 133.7751, 'currency_code' => 'AUD',
                'ports' => [
                    ['name' => 'Port of Sydney', 'lat' => -33.8491, 'lng' => 151.2120],
                    ['name' => 'Port of Melbourne', 'lat' => -37.8302, 'lng' => 144.9351],
                ]
            ],
        ];

        foreach ($countries as $code => $cData) {
            $country = Country::create([
                'code' => $code,
                'name' => $cData['name'],
                'official_name' => $cData['official_name'],
                'region' => $cData['region'],
                'latitude' => $cData['latitude'],
                'longitude' => $cData['longitude'],
                'currency_code' => $cData['currency_code']
            ]);

            foreach ($cData['ports'] as $port) {
                Port::create([
                    'country_id' => $country->id,
                    'name' => $port['name'],
                    'latitude' => $port['lat'],
                    'longitude' => $port['lng']
                ]);
            }
        }
    }
}