<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     */
    protected $fillable = [
        'service_id',
        'barber_id',
        'user_id',
        'customer_name',
        'customer_email',
        'start_at',
        'end_at',
        'status',
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     */
    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    /**
     * Define a relação: Um Agendamento (Appointment) pertence a um Serviço (Service).
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Define a relação: Um Agendamento (Appointment) pertence a um Utilizador (User).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ADICIONE ESTE MÉTODO - A RELAÇÃO QUE FALTAVA
     * Define a relação: Um Agendamento (Appointment) pertence a um Barbeiro (Barber).
     */
    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class);
    }
}