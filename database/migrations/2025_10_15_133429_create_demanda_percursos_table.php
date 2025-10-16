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
        Schema::create('demanda_percursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas')->onDelete('cascade'); // Se a demanda for apagada, os percursos também são.
            $table->integer('ordem'); // 1 para o ponto de partida, 2 para a primeira parada, etc.
            $table->text('endereco'); // Endereço completo
            $table->decimal('latitude', 10, 8)->nullable(); // Para o futuro uso com mapas
            $table->decimal('longitude', 11, 8)->nullable(); // Para o futuro uso com mapas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demanda_percursos');
    }
};
