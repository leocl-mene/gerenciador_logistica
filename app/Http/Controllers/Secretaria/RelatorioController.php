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
        // Busca todos os veículos para preencher o <select> no formulário
        $veiculos = Veiculo::orderBy('modelo')->get();

        // Busca o preço da gasolina (padrão '0.00' se não encontrar)
        $preco_gasolina = Setting::where('key', 'preco_gasolina')->first()->value ?? '0.00';
        
        return view('secretaria.relatorios.index', compact('veiculos', 'preco_gasolina'));
    }

    /**
     * Processa os filtros e gera a pré-visualização ou o download do Excel.
     */
    public function generate(Request $request)
    {
        // VALIDAÇÃO ATUALIZADA para aceitar um array de IDs
        $request->validate([
            'veiculos_ids' => 'required|array',
            'veiculos_ids.*' => 'exists:veiculos,id', // Garante que cada ID no array existe na tabela de veículos
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'action' => 'required|in:preview,download'
        ]);

        $veiculosIds = $request->veiculos_ids;
        $dataInicio = Carbon::parse($request->data_inicio)->startOfDay();
        $dataFim = Carbon::parse($request->data_fim)->endOfDay();
        
        // CONSULTA ATUALIZADA para usar whereIn e carregar a relação 'gpsTracks'
        $demandas = Demanda::whereIn('veiculo_id', $veiculosIds)
            ->where('status', 'Finalizada')
            ->whereBetween('data_finalizacao', [$dataInicio, $dataFim])
            // CORREÇÃO APLICADA AQUI: Adicionado 'gpsTracks' ao pré-carregamento
            ->with(['percursos', 'motoboy', 'veiculo', 'gpsTracks']) 
            ->orderBy('data_finalizacao', 'asc')
            ->get();
        
        $preco_gasolina = Setting::where('key', 'preco_gasolina')->first()->value ?? '0.00';

        if ($request->action == 'download') {
            $fileName = "Relatorio_Veiculos_{$dataInicio->format('d-m-Y')}_a_{$dataFim->format('d-m-Y')}.xlsx";
            
            // Passa o preço da gasolina para a classe de exportação
            return Excel::download(new VeiculoReportExport($demandas, $preco_gasolina), $fileName);
        }

        // Retorna para a view com os dados para a pré-visualização
        return view('secretaria.relatorios.index', [
            'veiculos' => Veiculo::orderBy('modelo')->get(),
            // Passa os IDs selecionados de volta para a view
            'veiculos_selecionados_ids' => $veiculosIds, 
            'demandas' => $demandas,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'preco_gasolina' => $preco_gasolina,
        ]);
    }
}
