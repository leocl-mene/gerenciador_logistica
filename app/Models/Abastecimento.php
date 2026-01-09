<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Abastecimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'veiculo_id',
        'valor',
        'data_abastecimento',
        'foto_url',
    ];

    protected $casts = [
        'data_abastecimento' => 'date',
        'valor' => 'decimal:2',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }
}
