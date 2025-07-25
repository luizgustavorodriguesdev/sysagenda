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

    protected $fillable = [
        'business_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
    ];

    /**
     * Define a relação: um Horário (Schedule) pertence a um Negócio (Business).
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}