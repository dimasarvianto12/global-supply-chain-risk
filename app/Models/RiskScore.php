<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    protected $fillable = ['country_id', 'weather_score', 'inflation_score', 'currency_score', 'sentiment_score', 'total_risk_score'];
}
