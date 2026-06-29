<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        // Data negara berdasarkan studi kasus di PDF
        $countries = [
            [
                'name' => 'Germany',
                'code' => 'DE',
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'region' => 'Europe',
                'languages' => 'German'
            ],
            [
                'name' => 'China',
                'code' => 'CN',
                'currency_code' => 'CNY',
                'currency_name' => 'Renminbi',
                'region' => 'Asia',
                'languages' => 'Chinese'
            ],
            [
                'name' => 'Indonesia',
                'code' => 'ID',
                'currency_code' => 'IDR',
                'currency_name' => 'Rupiah',
                'region' => 'Asia',
                'languages' => 'Indonesian'
            ],
            [
                'name' => 'Australia',
                'code' => 'AU',
                'currency_code' => 'AUD',
                'currency_name' => 'Australian Dollar',
                'region' => 'Oceania',
                'languages' => 'English'
            ],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(['code' => $country['code']], $country);
        }
    }
}