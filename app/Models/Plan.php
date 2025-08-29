<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    
    protected $fillable = ['name', 'slug', 'stripe_plan_id', 'price', 'description', 'barber_limit'];

    /**
     * Define a relação: Um Plano (Plan) tem muitos Utilizadores (User).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}


