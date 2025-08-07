<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Importa a classe correta do framework
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'duration_minutes',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * A relação Muitos-para-Muitos entre Serviço e Barbeiro.
     * A declaração de retorno agora corresponde exatamente ao que a função retorna.
     */
    public function barbers(): BelongsToMany
    {
        return $this->belongsToMany(Barber::class);
    }
}