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
    Schema::create('countries', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // Contoh: Germany, Indonesia [cite: 94, 96]
        $table->string('code', 5)->unique(); // ISO Code, contoh: DE, ID
        $table->string('currency_code', 10)->nullable(); // [cite: 66]
        $table->string('currency_name')->nullable(); // [cite: 66]
        $table->string('region')->nullable(); // [cite: 67]
        $table->string('languages')->nullable(); // [cite: 68]
        $table->bigInteger('gdp')->nullable(); // [cite: 57, 99]
        $table->decimal('inflation', 5, 2)->nullable(); // [cite: 58, 100]
        $table->bigInteger('population')->nullable(); // [cite: 59, 101]
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
