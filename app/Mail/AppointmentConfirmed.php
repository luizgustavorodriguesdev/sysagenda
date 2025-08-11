<?php

namespace App\Mail;

use App\Models\Appointment; // Importe o modelo Appointment
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * A instância do agendamento.
     * A propriedade precisa de ser pública para ser acessível na view.
     */
    public Appointment $appointment;

    /**
     * Create a new message instance.
     * O nosso construtor agora recebe o objeto do agendamento.
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Seu Agendamento foi Confirmado!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.appointments.confirmed',
        );
    }
}