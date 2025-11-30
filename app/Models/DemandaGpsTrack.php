<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandaGpsTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'demanda_id',
        'latitude',
        'longitude',
        'recorded_at',
    ];

    public $timestamps = false;
}
