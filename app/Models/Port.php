<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $fillable = ['country_id', 'name', 'latitude', 'longitude'];

    // Tambahkan fungsi relasi ini agar Port tahu mereka milik Country mana
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}