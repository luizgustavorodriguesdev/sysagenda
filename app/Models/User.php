<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Adicione esta linha no topo do arquivo

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // ADICIONAD CONSTANTES PARA DEFINIÇÃO DO TIPO DE USUÁRIO
    public const ROLE_CLIENT = 'client';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Não adicionamos 'role' aqui, pois usaremos o valor padrão da base de dados.
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
     * Define a relação: um Usuário (User) tem muitos Negócios (Business).
     */
    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    //ADICIONADO MÉTODO PARA VERIFICAR SE O USUÁRIO É ADMIN
    /*
    * Verifica se o usuário é um admin.
    */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    //ADICIONADO MÉTODO PARA VERIFICAR SE O USUÁRIO É CLIENTE
    /*
    * Verifica se o usuário é um cliente.
    */
    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }
    
    /**
     * Define a relação: um Usuário (User) tem muitos Agendamentos (Appointments).
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

}
