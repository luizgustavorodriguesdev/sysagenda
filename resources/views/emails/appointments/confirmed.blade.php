<x-mail::message>
# Olá, {{ $appointment->customer_name }}!

O seu agendamento foi confirmado com sucesso.

Aqui estão os detalhes:

**Serviço:** {{ $appointment->service->name }}
**Profissional:** {{ $appointment->barber->name }}
**Data:** {{ $appointment->start_at->format('d/m/Y') }}
**Hora:** {{ $appointment->start_at->format('H:i') }}

Obrigado por escolher os nossos serviços!

Atenciosamente,<br>
{{ config('app.name') }}
</x-mail::message>