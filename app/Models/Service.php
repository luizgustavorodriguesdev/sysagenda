<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'duration_minutes',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2', // Garante que o preço seja tratado como um decimal com 2 casas
    ];

    /**
     * Define a relação: um Serviço (Service) pertence a um Negócio (Business).
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Define a relação: um Serviço (Service) tem muitos Agendamentos (Appointment).
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}