<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            // Move motoboys to motorista (cargo 2)
            DB::table('users')->where('cargo_id', 3)->update(['cargo_id' => 2]);

            // Move secretaria/TI to administrador (cargo 1)
            DB::table('users')->where('cargo_id', 2)->update(['cargo_id' => 1]);

            // Rename cargos
            DB::table('cargos')->where('id', 1)->update(['nome_cargo' => 'Administrador']);
            DB::table('cargos')->where('id', 2)->update(['nome_cargo' => 'Motorista']);

            // Remove legacy cargo if it exists
            DB::table('cargos')->where('id', 3)->delete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            // Recreate legacy cargo if missing
            if (!DB::table('cargos')->where('id', 3)->exists()) {
                DB::table('cargos')->insert([
                    'id' => 3,
                    'nome_cargo' => 'Motoboy',
                ]);
            }

            // Restore cargo names
            DB::table('cargos')->where('id', 1)->update(['nome_cargo' => 'TI']);
            DB::table('cargos')->where('id', 2)->update(['nome_cargo' => 'Secretaria']);

            // Move motorista back to motoboy
            DB::table('users')->where('cargo_id', 2)->update(['cargo_id' => 3]);

            // Move admin back to secretaria
            DB::table('users')->where('cargo_id', 1)->update(['cargo_id' => 2]);
        });
    }
};
