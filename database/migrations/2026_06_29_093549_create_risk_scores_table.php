<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->float('weather_score')->default(0);
            $table->float('inflation_score')->default(0);
            $table->float('currency_score')->default(0);
            $table->float('sentiment_score')->default(0);
            $table->float('total_risk_score')->default(0);
            $table->timestamps(); // create_at otomatis berfungsi sebagai history log / tren scoring
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};