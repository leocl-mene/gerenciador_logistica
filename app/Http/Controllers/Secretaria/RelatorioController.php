<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Veiculo;
use App\Models\Demanda;
use App\Models\Abastecimento;
use App\Models\Setting;
use App\Models\User;
use App\Exports\VeiculoReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RelatorioController extends Controller
{
    /**
     * Mostra a página do formulário de filtros do relatório.
     */
    public function index(Request $request)
    {
        $veiculos = Veiculo::with('motoboys')->orderBy('modelo')->get();
        $motoboys = User::where('cargo_id', User::ROLE_MOTORISTA)->orderBy('name')->get();

        $preco_gasolina = Setting::where('key', 'preco_gasolina')->first()->value ?? '0.00';
        
        return view('secretaria.relatorios.index', [
            'veiculos' => $veiculos,
            'motoboys' => $motoboys,
            'preco_gasolina' => $preco_gasolina,
            'tab' => $request->get('tab', 'demandas'),
        ]);
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
            'action' => 'required|in:preview,download',
            'motoboy_id' => 'nullable|exists:users,id',
        ]);

        $veiculosIds = $request->veiculos_ids;
        $motoboyId = $request->motoboy_id;
        $dataInicio  = Carbon::parse($request->data_inicio)->startOfDay();
        $dataFim     = Carbon::parse($request->data_fim)->endOfDay();
        
        // 🔥 ATUALIZADO: percursos foi removido do sistema
        $query = Demanda::whereIn('veiculo_id', $veiculosIds)
            ->where('status', 'Finalizada')
            ->whereBetween('data_finalizacao', [$dataInicio, $dataFim])
            ->with([
                'motoboy',
                'veiculo',
                'gpsTracks',
                'fotosKm',
                'percursos',
            ])
            ->orderBy('data_finalizacao', 'asc');

        if (!empty($motoboyId)) {
            $query->where('motoboy_id', $motoboyId);
        }

        $demandas = $query->get();

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
            'veiculos' => Veiculo::with('motoboys')->orderBy('modelo')->get(),
            'motoboys' => User::where('cargo_id', User::ROLE_MOTORISTA)->orderBy('name')->get(),
            'veiculos_selecionados_ids' => $veiculosIds,
            'motoboy_id' => $motoboyId,
            'demandas' => $demandas,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'preco_gasolina' => $preco_gasolina,
            'tab' => 'demandas',
        ]);
    }

    /**
     * Processa filtros e mostra o relatorio de abastecimentos.
     */
    public function abastecimentos(Request $request)
    {
        $veiculos = Veiculo::with('motoboys')->orderBy('modelo')->get();
        $preco_gasolina = Setting::where('key', 'preco_gasolina')->first()->value ?? '0.00';

        $abastecimentos = collect();
        $veiculosIds = $request->input('veiculos_ids', []);

        if ($request->filled('data_inicio') && $request->filled('data_fim')) {
            $request->validate([
                'data_inicio' => 'required|date',
                'data_fim' => 'required|date|after_or_equal:data_inicio',
                'veiculos_ids' => 'nullable|array',
                'veiculos_ids.*' => 'exists:veiculos,id',
            ]);

            $dataInicio = Carbon::parse($request->data_inicio)->startOfDay();
            $dataFim = Carbon::parse($request->data_fim)->endOfDay();

            $query = Abastecimento::with(['usuario', 'veiculo'])
                ->whereBetween('data_abastecimento', [$dataInicio, $dataFim])
                ->orderBy('data_abastecimento', 'asc');

            if (!empty($veiculosIds)) {
                $query->whereIn('veiculo_id', $veiculosIds);
            }

            $abastecimentos = $query->get();
        }

        return view('secretaria.relatorios.index', [
            'veiculos' => $veiculos,
            'veiculos_selecionados_ids' => $veiculosIds,
            'abastecimentos' => $abastecimentos,
            'data_inicio' => $request->data_inicio,
            'data_fim' => $request->data_fim,
            'preco_gasolina' => $preco_gasolina,
            'tab' => 'abastecimentos',
        ]);
    }

    public function destroyAbastecimento(Abastecimento $abastecimento)
    {
        $fotoUrl = $abastecimento->foto_url;
        if (!empty($fotoUrl)) {
            $publicBase = Storage::disk('public')->url('/');
            if (str_starts_with($fotoUrl, $publicBase)) {
                $relativePath = ltrim(substr($fotoUrl, strlen($publicBase)), '/');
                if ($relativePath !== '') {
                    Storage::disk('public')->delete($relativePath);
                }
            }
        }

        $abastecimento->delete();

        return back()->with('abastecimento_success', 'Abastecimento excluido com sucesso.');
    }
}
