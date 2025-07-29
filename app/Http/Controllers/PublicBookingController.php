<?php
// app/Http/Controllers/PublicBookingController.php

namespace App\Http\Controllers;

use App\Models\Business; // Importe o Model Business
use App\Models\Service;
use Carbon\Carbon;

use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    /**
     * Exibe a página pública de um negócio com seus serviços.
     *
     * @param \App\Models\Business $business O objeto do negócio injetado pelo Route-Model Binding.
     * @return \Illuminate\View\View
     */
    public function show(Business $business)
    {
        // O Laravel já nos entrega o $business correto graças à rota.
        // Agora, só precisamos carregar os serviços associados a ele.
        $services = $business->services()->get();

        // Retorna a view pública, passando o negócio e seus serviços.
        return view('public.show', compact('business', 'services'));
    }

    /**
     * Calcula e retorna os horários disponíveis para um serviço numa data específica.
     *
     * @param \App\Models\Service $service O serviço para o qual queremos a disponibilidade.
     * @param string $date A data no formato 'AAAA-MM-DD'.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailability(Service $service, $date)
    {
        // 1. PREPARAÇÃO DOS DADOS

        // Converte a string da data recebida (ex: "2025-07-28") num objeto Carbon.
        // O startOfDay() garante que estamos a considerar o início do dia (00:00:00).
        $bookingDate = Carbon::parse($date)->startOfDay();

        // Pega o negócio associado a este serviço.
        $business = $service->business;

        // Obtém o dia da semana (0=Domingo, 1=Segunda, ..., 6=Sábado) a partir da data.
        $dayOfWeek = $bookingDate->dayOfWeek;

        // Procura o horário de atendimento do negócio para aquele dia da semana específico.
        $schedule = $business->schedules()->where('day_of_week', $dayOfWeek)->first();

        // Se o negócio não trabalha neste dia (não há registo de horário), retorna uma lista vazia.
        if (!$schedule) {
            return response()->json(['available_slots' => []]);
        }


        // 2. GERAR TODOS OS POTENCIAIS HORÁRIOS

        // Pega a duração do serviço em minutos.
        $serviceDuration = $service->duration_minutes;

        // Define o início e o fim do dia de trabalho com base no horário guardado.
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);

        $allSlots = [];
        // Começa no início do expediente e vai adicionando a duração do serviço
        // até chegar à hora de fim do expediente.
        while ($startTime < $endTime) {
            // Adiciona o horário atual (formatado como HH:MM) à lista de todos os horários possíveis.
            $allSlots[] = $startTime->format('H:i');
            // Avança o tempo pela duração do serviço.
            $startTime->addMinutes($serviceDuration);
        }


        // 3. FILTRAR HORÁRIOS JÁ OCUPADOS

        // Busca todos os agendamentos já existentes para este serviço nesta data específica.
        $existingAppointments = $service->appointments()
                                        ->whereDate('start_at', $bookingDate)
                                        ->get()
                                        ->pluck('start_at'); // Extrai apenas a coluna 'start_at'

        // Converte os horários dos agendamentos existentes para o formato "HH:MM".
        $bookedSlots = $existingAppointments->map(function ($appointmentTime) {
            return Carbon::parse($appointmentTime)->format('H:i');
        })->toArray(); // Converte a coleção para um array simples.

        // Compara a lista de todos os horários possíveis com a lista de horários já ocupados,
        // e retorna apenas os que NÃO estão na lista de ocupados.
        $availableSlots = array_diff($allSlots, $bookedSlots);


        // 4. RETORNAR O RESULTADO

        // Retorna a lista final de horários disponíveis como uma resposta JSON.
        return response()->json(['available_slots' => array_values($availableSlots)]);
    }

    public function storeBooking(Request $request)
    {
        // 1. VALIDAÇÃO DOS DADOS
        // Garante que todos os dados necessários foram enviados e são válidos.
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        // 2. PREPARAÇÃO E VERIFICAÇÃO FINAL
        $service = Service::find($validated['service_id']);
        // Combina a data e a hora para criar um objeto Carbon completo para o início do agendamento.
        $startAt = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);
        // Calcula a hora de término somando a duração do serviço.
        $endAt = $startAt->copy()->addMinutes($service->duration_minutes);

        // Verificação de segurança (anti race condition):
        // Verifica se, no último segundo, alguém já não agendou neste mesmo horário.
        $existingAppointment = \App\Models\Appointment::where('start_at', $startAt)
            ->where('service_id', $service->id)
            ->exists();

        // Se o horário já foi ocupado, retorna um erro em formato JSON.
        if ($existingAppointment) {
            return response()->json(['error' => 'Desculpe, este horário acabou de ser preenchido. Por favor, escolha outro.'], 409); // 409 Conflict
        }

        // 3. CRIAÇÃO DO AGENDAMENTO
        // Se tudo estiver certo, cria o novo agendamento na base de dados.
        \App\Models\Appointment::create([
            'service_id' => $service->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => 'confirmed',
        ]);

        // 4. RESPOSTA DE SUCESSO
        // Retorna uma resposta de sucesso em JSON.
        return response()->json(['success' => 'Agendamento confirmado com sucesso!']);
    }

}