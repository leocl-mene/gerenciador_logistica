<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Demanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'is_priority',
        'status',
        'secretaria_id',
        'tipo',              // normal | urgente
        'motoboy_id',
        'veiculo_id',
        'km_inicial',
        'km_final',
        'data_aceite',
        'data_finalizacao',
    ];

    protected $casts = [
        'is_priority'      => 'boolean',
        'data_aceite'      => 'datetime',
        'data_finalizacao' => 'datetime',
    ];

    /**
     * Secretaria que criou a demanda
     */
    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretaria_id');
    }

    /**
     * Motoboy responsável
     */
    public function motoboy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'motoboy_id');
    }

    /**
     * Percursos cadastrados na secretaria (usado no painel web)
     */
    public function percursos(): HasMany
    {
        return $this->hasMany(DemandaPercurso::class)->orderBy('ordem');
    }

    /**
     * Veículo utilizado
     */
    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    /**
     * Registro de fotos de KM (inicial/final)
     */
    public function fotosKm(): HasOne
    {
        return $this->hasOne(DemandasFotosKm::class);
    }

    /**
     * Tracks de GPS enviados pelo app (para demandas urgentes)
     */
    public function gpsTracks(): HasMany
    {
        return $this->hasMany(DemandaGpsTrack::class)->orderBy('recorded_at');
    }
}
