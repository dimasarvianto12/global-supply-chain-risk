<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCache extends Model
{
    protected $fillable = ['country_id', 'title', 'description', 'url', 'source_name', 'sentiment_label', 'published_at'];
}
