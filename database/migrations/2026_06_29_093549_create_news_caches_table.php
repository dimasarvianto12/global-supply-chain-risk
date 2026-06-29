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
    Schema::create('news_caches', function (Blueprint $table) {
        $table->id();
        $table->foreignId('country_id')->constrained()->onDelete('cascade');
        $table->string('title');
        $table->text('description')->nullable();
        $table->string('url');
        $table->string('source_name')->nullable();
        $table->string('sentiment')->default('Neutral'); // Positive, Neutral, Negative [cite: 165-167]
        $table->integer('positive_score')->default(0); // [cite: 193]
        $table->integer('negative_score')->default(0); // [cite: 195]
        $table->dateTime('published_at');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_caches');
    }
};
