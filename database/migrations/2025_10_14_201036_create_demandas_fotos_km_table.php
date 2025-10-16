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
        Schema::create('demandas_fotos_km', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas')->onDelete('cascade');
            $table->string('foto_url_inicio');
            $table->string('foto_url_final')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandas_fotos_km');
    }
};