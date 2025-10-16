<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // Import adicionado

class Demanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'is_priority',
        'status',
        'secretaria_id',
        'tipo', // Adicionado anteriormente
        'motoboy_id',
        'veiculo_id',
        'km_inicial',  // <-- ADICIONADO
        'km_final',    // <-- ADICIONADO
        'data_aceite',
        'data_finalizacao',
    ];

    /**
     * Define a relação: Uma Demanda pertence a uma Secretaria (User).
     */
    public function secretaria(): BelongsTo
    {
        // O segundo argumento ('secretaria_id') é a chave estrangeira na tabela 'demandas'
        return $this->belongsTo(User::class, 'secretaria_id');
    }

    /**
     * Define a relação: Uma Demanda pertence a um Motoboy (User).
     */
    public function motoboy(): BelongsTo
    {
        // O segundo argumento ('motoboy_id') é a chave estrangeira na tabela 'demandas'
        return $this->belongsTo(User::class, 'motoboy_id');
    }

    /**
     * Define a relação: Uma Demanda tem muitos Percursos.
     */
    public function percursos(): HasMany
    {
        return $this->hasMany(DemandaPercurso::class)->orderBy('ordem');
    }

    /**
     * Define a relação: Uma Demanda tem um Veículo associado (chave estrangeira 'veiculo_id').
     */
    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    /**
     * Define a relação: Uma Demanda tem um registro de fotos de KM.
     */
    public function fotosKm(): HasOne
    {
        return $this->hasOne(DemandasFotosKm::class);
    }
}