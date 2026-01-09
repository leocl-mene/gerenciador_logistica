<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // IMPORT ADICIONADO
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 1;
    public const ROLE_MOTORISTA = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cargo_id',
        'telefone',
        'fcm_token', // Adicionado para permitir o cadastro de telefone
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define a relação: Um User (Secretaria) pode ter criado muitas Demandas.
     */
    public function demandasCriadas(): HasMany
    {
        return $this->hasMany(Demanda::class, 'secretaria_id');
    }

    /**
     * Define a relação: Um User (Motoboy) pode ter muitas Demandas atribuídas.
     */
    public function demandasAtribuidas(): HasMany
    {
        return $this->hasMany(Demanda::class, 'motoboy_id');
    }

    /**
     * Os veículos que este motoboy pode usar.
     */
    public function veiculos(): BelongsToMany
    {
        // O Laravel vai procurar a tabela 'motoboy_veiculos'
        // e usar as chaves 'user_id' e 'veiculo_id'.
        return $this->belongsToMany(Veiculo::class, 'motoboy_veiculos');
    }

    public function abastecimentos(): HasMany
    {
        return $this->hasMany(Abastecimento::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->cargo_id === self::ROLE_ADMIN;
    }

    public function isMotorista(): bool
    {
        return $this->cargo_id === self::ROLE_MOTORISTA;
    }
}
