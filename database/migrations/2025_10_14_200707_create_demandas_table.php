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
        Schema::create('demandas', function (Blueprint $table) {
            $table->id();
            // ID de quem criou (Secretaria) e de quem aceitou (Motoboy)
            $table->foreignId('secretaria_id')->constrained('users');
            $table->foreignId('motoboy_id')->nullable()->constrained('users');

            $table->enum('status', ['Pendente', 'Aceita', 'Em Rota', 'Finalizada', 'Cancelada'])->default('Pendente');
            $table->decimal('km_estimado', 10, 2)->nullable();

            $table->timestamp('data_aceite')->nullable();
            $table->timestamp('data_finalizacao')->nullable();
            $table->timestamps(); // data_criacao (created_at) e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandas');
    }
};