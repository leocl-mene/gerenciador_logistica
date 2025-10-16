<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting; // Importação adicionada

class SettingController extends Controller
{
    /**
     * Atualiza a configuração do preço da gasolina.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // 1. Validação: garante que o campo seja preenchido, seja numérico e não seja negativo.
        $request->validate([
            'preco_gasolina' => 'required|numeric|min:0',
        ]);

        // 2. Persistência: Encontra a configuração 'preco_gasolina' ou a cria se não existir,
        // e define seu valor com o dado enviado pelo formulário.
        Setting::updateOrCreate(
            ['key' => 'preco_gasolina'],
            ['value' => $request->preco_gasolina]
        );

        // 3. Retorno: Redireciona de volta com uma mensagem de sucesso.
        return back()->with('success', 'Preço da gasolina atualizado com sucesso!');
    }
}
