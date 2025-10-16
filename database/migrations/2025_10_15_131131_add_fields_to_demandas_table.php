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
        Schema::table('demandas', function (Blueprint $table) {
        $table->string('titulo')->after('id'); // Título da demanda
        $table->text('descricao')->nullable()->after('titulo'); // Descrição opcional
        $table->boolean('is_priority')->default(false)->after('status'); // Campo para prioridade
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void // O método 'down' reverte as alterações
    {
        Schema::table('demandas', function (Blueprint $table) {
        $table->dropColumn(['titulo', 'descricao', 'is_priority']);
        });
    }
};
