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
        Schema::table('veiculos', function (Blueprint $table) {
            // Adiciona a coluna para armazenar o consumo médio do veículo.
            // É um campo decimal com 8 dígitos no total e 2 casas decimais, permitindo valores nulos.
            $table->decimal('km_por_litro', 8, 2)->nullable()->after('ano');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('veiculos', function (Blueprint $table) {
            // Remove a coluna em caso de rollback
            $table->dropColumn('km_por_litro');
        });
    }
};
