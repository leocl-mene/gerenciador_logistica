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
        // 1. CRIA A TABELA 'USERS'
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // NOSSOS CAMPOS PERSONALIZADOS - INÍCIO
            $table->string('telefone', 20)->nullable();
            
            // AQUI É O LUGAR CORRETO PARA A CHAVE ESTRANGEIRA
            $table->unsignedBigInteger('cargo_id');
            $table->foreign('cargo_id')->references('id')->on('cargos');

            $table->boolean('ativo')->default(true);

            // Campos para rastreamento
            $table->boolean('status_online')->default(false);
            $table->decimal('ultima_latitude', 10, 8)->nullable();
            $table->decimal('ultima_longitude', 11, 8)->nullable();
            $table->timestamp('ultimo_update')->nullable();
            // NOSSOS CAMPOS PERSONALIZADOS - FIM

            $table->rememberToken();
            $table->timestamps();
        });

        // 2. CRIA A TABELA 'PASSWORD_RESET_TOKENS'
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
            // A linha do erro foi removida daqui. Esta tabela não tem relação com 'cargos'.
        });

        // 3. CRIA A TABELA 'SESSIONS'
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};