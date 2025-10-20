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
        'tipo',
        'modelo',
        'marca',
        'ano',
        // 'km_por_litro' foi removido daqui
    ];

    /**
     * Retorna o consumo padrão (km/l) com base no modelo do veículo.
     */
    public function getConsumoPadraoAttribute(): float
    {
        $modeloLower = strtolower($this->modelo);

        // PRIMEIRO, verifica se é uma moto.
        if (str_contains($modeloLower, 'moto') || str_contains($modeloLower, 'moto')) {
            return 40.0; // Média para a moto
        }

        // DEPOIS, verifica se é um Spin.
        if (str_contains($modeloLower, 'carro')) {
            return 10.5; // Média para Spin na cidade com gasolina
        }

        // Um valor padrão caso o modelo não seja reconhecido
        // Isso evita erros de divisão por zero.
        return 1.0;
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