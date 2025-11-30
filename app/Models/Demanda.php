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
        'tipo',
        'motoboy_id',
        'veiculo_id',
        'km_inicial',
        'km_final',
        'data_aceite',
        'data_finalizacao',
    ];

    public function secretaria(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretaria_id');
    }

    public function motoboy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'motoboy_id');
    }

    /**
     * ROTA REAL DO PERCURSO (única tabela usada agora)
     */
    public function gpsTracks(): HasMany
    {
        return $this->hasMany(DemandaGpsTrack::class)->orderBy('recorded_at');
    }

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function fotosKm(): HasOne
    {
        return $this->hasOne(DemandasFotosKm::class);
    }
}
