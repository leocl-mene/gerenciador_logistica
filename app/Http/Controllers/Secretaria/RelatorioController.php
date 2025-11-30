<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Veiculo;
use App\Models\Demanda;
use App\Models\Setting;
use App\Exports\VeiculoReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Mostra a página do formulário de filtros do relatório.
     */
    public function index()
    {
        $veiculos = Veiculo::orderBy('modelo')->get();

        $preco_gasolina = Setting::where('key', 'preco_gasolina')->first()->value ?? '0.00';
        
        return view('secretaria.relatorios.index', compact('veiculos', 'preco_gasolina'));
    }

    /**
     * Processa os filtros e gera a pré-visualização ou o download do Excel.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'veiculos_ids' => 'required|array',
            'veiculos_ids.*' => 'exists:veiculos,id',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'action' => 'required|in:preview,download'
        ]);

        $veiculosIds = $request->veiculos_ids;
        $dataInicio  = Carbon::parse($request->data_inicio)->startOfDay();
        $dataFim     = Carbon::parse($request->data_fim)->endOfDay();
        
        // 🔥 ATUALIZADO: percursos foi removido do sistema
        $demandas = Demanda::whereIn('veiculo_id', $veiculosIds)
            ->where('status', 'Finalizada')
            ->whereBetween('data_finalizacao', [$dataInicio, $dataFim])
            ->with([
                'motoboy',
                'veiculo',
                'gpsTracks',
                'fotosKm'
            ])
            ->orderBy('data_finalizacao', 'asc')
            ->get();

        $preco_gasolina = Setting::where('key', 'preco_gasolina')->first()->value ?? '0.00';

        if ($request->action == 'download') {
            $fileName = "Relatorio_Veiculos_{$dataInicio->format('d-m-Y')}_a_{$dataFim->format('d-m-Y')}.xlsx";
            return Excel::download(
                new VeiculoReportExport($demandas, $preco_gasolina),
                $fileName
            );
        }

        // Prévia na tela
        return view('secretaria.relatorios.index', [
            'veiculos' => Veiculo::orderBy('modelo')->get(),
            'veiculos_selecionados_ids' => $veiculosIds,
            'demandas' => $demandas,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'preco_gasolina' => $preco_gasolina,
        ]);
    }
}
