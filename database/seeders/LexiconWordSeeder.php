<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PositiveWord;
use App\Models\NegativeWord;

class LexiconWordSeeder extends Seeder
{
    public function run(): void
    {
        // Kata kunci sesuai dengan lampiran contoh di PDF halaman 7
        $positives = ['growth', 'increase', 'profit', 'stable', 'improve', 'safe', 'boom', 'surplus', 'efficient'];
        $negatives = ['war', 'crisis', 'inflation', 'delay', 'disaster', 'decrease', 'risk', 'blockade', 'congested'];

        foreach ($positives as $w) { PositiveWord::firstOrCreate(['word' => $w]); }
        foreach ($negatives as $w) { NegativeWord::firstOrCreate(['word' => $w]); }
    }
}