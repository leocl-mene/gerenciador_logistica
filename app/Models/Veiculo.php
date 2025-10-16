<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // IMPORT ADICIONADO

class Veiculo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'placa',
        'modelo',
        'marca',
        'ano',
        // 'km_por_litro' foi removido daqui
    ];

    /**
     * Retorna o consumo padrão (km/l) com base no modelo do veículo.
     * Este é um "Accessor" do Laravel.
     */
    public function getConsumoPadraoAttribute(): float
    {
        // O str_contains verifica se a string 'Spin' ou 'Yamaha' existe no nome do modelo
        if (str_contains(strtolower($this->modelo), 'spin')) {
            return 10.5; // Média para Spin na cidade com gasolina
        }

        if (str_contains(strtolower($this->modelo), 'yamaha')) {
            return 45.0; // Média para a moto
        }

        // Um valor padrão caso o modelo não seja reconhecido (e para evitar divisão por zero)
        return 10.0; // Novo padrão mais razoável, se for 1.0 causará custo muito alto
    }

    /**
     * Os motoboys que podem usar este veículo (relação Many-to-Many).
     */
    public function motoboys(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'motoboy_veiculos', 'veiculo_id', 'user_id');
    }

    // A partir de agora, o consumo de KM/L pode ser acessado via $veiculo->consumo_padrao
}