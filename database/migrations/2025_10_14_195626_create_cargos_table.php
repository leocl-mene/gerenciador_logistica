<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Importação necessária

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nome_cargo', 50)->unique();
        });

        // Inserindo os cargos padrão do sistema
        DB::table('cargos')->insert([
            ['nome_cargo' => 'TI'],
            ['nome_cargo' => 'Secretaria'],
            ['nome_cargo' => 'Motoboy']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};