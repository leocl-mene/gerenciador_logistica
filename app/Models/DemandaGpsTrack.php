<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandaGpsTrack extends Model
{
    protected $fillable = ['demanda_id', 'latitude', 'longitude', 'recorded_at'];
    
    // Indica que o modelo não deve usar as colunas created_at e updated_at
    public $timestamps = false; 

    // Opcional: Definir o nome da tabela explicitamente se for diferente do padrão (demanda_gps_tracks)
    // protected $table = 'demanda_gps_track'; 
}