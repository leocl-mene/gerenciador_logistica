<?php

namespace App\Exports;

use App\Models\Demanda;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VeiculoReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $demandas;
    protected $preco_gasolina; // Adicionado para receber o preço

    public function __construct($demandas, $preco_gasolina)
    {
        $this->demandas = $demandas;
        $this->preco_gasolina = $preco_gasolina;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->demandas;
    }

    /**
     * Define os títulos das colunas.
     */
    public function headings(): array
    {
        return [
            'DATA',
            'VEICULO', // NOVO: Adiciona coluna Veículo ao Excel
            'PERCURSO',
            'KM INICIAL',
            'KM FINAL',
            'KM RODADO',
            'MOTORISTA',
            'CUSTO (R$)',
        ];
    }

    /**
     * Mapeia os dados de cada demanda para as colunas.
     * @var Demanda $demanda
     */
    public function map($demanda): array
    {
        // Lógica de percurso ATUALIZADA
        $percursoString = 'N/A';
        if ($demanda->tipo === 'urgente' && $demanda->gpsTracks->count() > 0) {
            $percursoString = "Rota Urgente (Gravada via GPS)";
        } else if ($demanda->percursos->count() > 0) {
            $percursoString = $demanda->percursos->pluck('endereco')->join(' -> ');
        }

        $kmRodado = $demanda->km_final - $demanda->km_inicial;

        // Calcula o custo do combustível
        $custo = 0;
        if ($demanda->veiculo && $demanda->veiculo->consumo_padrao > 1) {
            $custo = ($kmRodado / $demanda->veiculo->consumo_padrao) * $this->preco_gasolina;
        }

        return [
            // DATA
            \Carbon\Carbon::parse($demanda->data_finalizacao)->format('d/m/Y'),
            // VEÍCULO (NOVO)
            $demanda->veiculo->placa ?? 'N/A',
            // PERCURSO (Lógica aplicada)
            $percursoString,
            // KM INICIAL
            $demanda->km_inicial,
            // KM FINAL
            $demanda->km_final,
            // KM RODADO (calculado)
            $kmRodado,
            // MOTORISTA
            $demanda->motoboy->name ?? 'N/A',
            // CUSTO
            number_format($custo, 2, '.', ''), 
        ];
    }
}