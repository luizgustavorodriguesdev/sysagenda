<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// Importa a classe correta do framework
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Barber extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'email',
        'phone',
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * A relação Muitos-para-Muitos entre Barbeiro e Serviço.
     * A declaração de retorno agora corresponde exatamente ao que a função retorna.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
}