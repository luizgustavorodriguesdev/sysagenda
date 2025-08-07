<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;

    // Como os horários são de configuração, não precisam de created_at/updated_at
    public $timestamps = false;

    // Alteramos 'business_id' para 'barber_id'
    protected $fillable = [
        'barber_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    /**
     * A relação agora é com Barber, não com Business.
     * Um Horário (Schedule) pertence a um Barbeiro (Barber).
     */
    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class);
    }
}