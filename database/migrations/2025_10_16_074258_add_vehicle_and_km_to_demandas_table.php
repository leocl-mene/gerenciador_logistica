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
            $table->foreignId('veiculo_id')->nullable()->constrained('veiculos')->after('motoboy_id');
            $table->unsignedBigInteger('km_inicial')->nullable()->after('km_estimado');
            $table->unsignedBigInteger('km_final')->nullable()->after('km_inicial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandas', function (Blueprint $table) {
            $table->dropForeign(['veiculo_id']);
            $table->dropColumn(['veiculo_id', 'km_inicial', 'km_final']);
        });
    }
};
