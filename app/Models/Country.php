<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['code', 'name', 'official_name', 'region', 'latitude', 'longitude', 'currency_code'];

    public function ports() { return $this->hasMany(Port::class); }
    public function riskScores() { return $this->hasMany(RiskScore::class); }
}
