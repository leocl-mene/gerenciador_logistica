<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- A linha que faltava
use Illuminate\Database\Eloquent\Model;

class DemandasFotosKm extends Model
{
    use HasFactory; // Agora esta linha funciona

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demandas_fotos_km';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'demanda_id',
        'foto_url_inicio',
        'foto_url_final',
    ];
}
