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
            // Remove a coluna km_por_litro da tabela veiculos
            $table->dropColumn('km_por_litro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('veiculos', function (Blueprint $table) {
            // Adiciona a coluna km_por_litro de volta em caso de rollback
            $table->decimal('km_por_litro', 8, 2)->nullable()->after('ano');
        });
    }
};