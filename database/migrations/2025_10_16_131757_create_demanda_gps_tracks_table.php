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
        Schema::create('demanda_gps_tracks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('recorded_at'); // Hora em que o ponto foi gravado
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demanda_gps_tracks');
    }
};
