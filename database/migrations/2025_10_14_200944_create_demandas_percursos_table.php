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
        Schema::create('demandas_percursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas')->onDelete('cascade'); // Se a demanda for apagada, o percurso também é.
            $table->integer('ordem');
            $table->text('endereco_completo');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandas_percursos');
    }
};