<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Abastecimento;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbastecimentoController extends Controller
{
    /**
     * Registra um abastecimento via app.
     */
    public function store(Request $request)
    {
        $request->validate([
            'veiculo_id' => 'required|exists:veiculos,id',
            'valor' => 'required|numeric|min:0.01',
            'data_abastecimento' => 'required|date_format:d/m/Y',
            'foto_cupom' => 'required|image|max:5120',
        ]);

        $path = $request->file('foto_cupom')->store('abastecimentos', 'public');
        $url = Storage::disk('public')->url($path);

        $abastecimento = Abastecimento::create([
            'user_id' => Auth::id(),
            'veiculo_id' => $request->veiculo_id,
            'valor' => $request->valor,
            'data_abastecimento' => Carbon::createFromFormat('d/m/Y', $request->data_abastecimento)->startOfDay(),
            'foto_url' => $url,
        ]);

        return response()->json([
            'message' => 'Abastecimento registrado com sucesso.',
            'abastecimento' => $abastecimento,
        ], 201);
    }
}
