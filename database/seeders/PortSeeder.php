<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Port;
use App\Models\Country;

class PortSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data negara yang sudah di-seed untuk mendapatkan ID-nya
        $indonesia = Country::where('code', 'ID')->first();
        $germany = Country::where('code', 'DE')->first();
        $china = Country::where('code', 'CN')->first();
        $australia = Country::where('code', 'AU')->first();

        $ports = [
            // Indonesia
            [
                'country_id' => $indonesia->id,
                'port_name' => 'Tanjung Priok (Jakarta)',
                'latitude' => -6.1014,
                'longitude' => 106.8824,
            ],
            // Jerman
            [
                'country_id' => $germany->id,
                'port_name' => 'Port of Hamburg',
                'latitude' => 53.5452,
                'longitude' => 9.9530,
            ],
            // China
            [
                'country_id' => $china->id,
                'port_name' => 'Port of Shanghai',
                'latitude' => 30.6247,
                'longitude' => 122.0664,
            ],
            // Australia
            [
                'country_id' => $australia->id,
                'port_name' => 'Port of Sydney',
                'latitude' => -33.8491,
                'longitude' => 151.2111,
            ],
        ];

        foreach ($ports as $port) {
            Port::create($port);
        }
    }
}