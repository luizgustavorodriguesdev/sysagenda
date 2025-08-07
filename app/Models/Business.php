<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'branch',
    ];

    /**
     * Define a relação: um Negócio (Business) pertence a um Usuário (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a relação: um Negócio (Business) tem muitos Serviços (Service).
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Define a relação: um Negócio (Business) tem muitos Horários (Schedule).
     */
    /*public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }*/
        
    /**
     * Define a relação: um Negócio (Business) tem muitos Barbeiros (Barber).
     */
    public function barbers(): HasMany
    {
        return $this->hasMany(Barber::class);
    }
}