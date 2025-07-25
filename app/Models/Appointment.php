<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'customer_name',
        'customer_email',
        'start_at',
        'end_at',
        'status',
    ];

    protected $casts = [
        'start_at' => 'datetime', // Garante que estas colunas sejam tratadas como objetos Carbon/DateTime
        'end_at' => 'datetime',
    ];

    /**
     * Define a relação: um Agendamento (Appointment) pertence a um Serviço (Service).
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}