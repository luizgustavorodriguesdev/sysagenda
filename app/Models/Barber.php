<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barber extends Model
{
    use HasFactory;
    //HasFactory é um trait do Laravel usado em modelos Eloquent para facilitar a criação de instâncias de modelos em testes usando factories.

    /**
     * Os atributos que são atribuíveis em massa.
    */
    protected $fillable = ['business_id', 'name', 'email', 'phone'];
    
    /**
     * Define a relação com o modelo Business.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
