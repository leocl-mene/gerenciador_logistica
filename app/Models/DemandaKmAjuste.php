<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandaKmAjuste extends Model
{
    use HasFactory;

    protected $fillable = [
        'demanda_id',
        'user_id',
        'km_inicial_before',
        'km_final_before',
        'km_inicial_after',
        'km_final_after',
    ];

    public function demanda(): BelongsTo
    {
        return $this->belongsTo(Demanda::class, 'demanda_id');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
