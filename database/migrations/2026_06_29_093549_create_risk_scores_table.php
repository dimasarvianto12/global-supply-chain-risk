<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('risk_scores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('country_id')->constrained()->onDelete('cascade');
        $table->decimal('weather_risk', 5, 2)->default(0); // Bobot cuaca [cite: 107, 218]
        $table->decimal('inflation_risk', 5, 2)->default(0); // Bobot inflasi [cite: 108, 219]
        $table->decimal('news_risk', 5, 2)->default(0); // Bobot sentimen berita [cite: 109, 220]
        $table->decimal('currency_risk', 5, 2)->default(0); // Bobot kurs [cite: 109, 221]
        $table->decimal('total_risk', 5, 2)->default(0); // Total Risk Score [cite: 106, 222]
        $table->string('risk_level', 20)->default('Low Risk'); // Low, Medium, High [cite: 112-113]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};
