<?php

namespace App\Http\Controllers;

// Certifique-se de que todas estas declarações 'use' estão presentes
use App\Models\Business;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    /**
     * Exibe a página pública de um negócio com seus serviços.
     */
    public function show(Business $business)
    {
        $services = $business->services()->get();
        return view('public.show', compact('business', 'services'));
    }

    /**
     * Calcula e retorna os horários disponíveis para um serviço numa data específica.
     */
    public function getAvailability(Service $service, $date)
    {
        // Converte a string da data para um objeto Carbon
        $bookingDate = Carbon::parse($date)->startOfDay();
        $business = $service->business;

        // Pega o dia da semana (0 para Domingo, 1 para Segunda, etc.)
        $dayOfWeek = $bookingDate->dayOfWeek;

        // Procura o horário de atendimento para aquele dia da semana
        $schedule = $business->schedules()->where('day_of_week', $dayOfWeek)->first();

        // Se não houver horário definido para este dia, retorna uma lista vazia
        if (!$schedule) {
            return response()->json(['available_slots' => []]);
        }

        // Gera todos os possíveis horários do dia
        $serviceDuration = $service->duration_minutes;
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        $allSlots = [];
        while ($startTime < $endTime) {
            $allSlots[] = $startTime->format('H:i');
            $startTime->addMinutes($serviceDuration);
        }

        // Busca os agendamentos já existentes para filtrar
        $existingAppointments = $service->appointments()
            ->whereDate('start_at', $bookingDate)
            ->get()
            ->pluck('start_at');

        $bookedSlots = $existingAppointments->map(function ($appointmentTime) {
            return Carbon::parse($appointmentTime)->format('H:i');
        })->toArray();

        // Retorna apenas os horários que não foram agendados
        $availableSlots = array_diff($allSlots, $bookedSlots);
        return response()->json(['available_slots' => array_values($availableSlots)]);
    }

    /**
     * Salva um novo agendamento no banco de dados.
     */
    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        $service = Service::find($validated['service_id']);
        $startAt = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);
        $endAt = $startAt->copy()->addMinutes($service->duration_minutes);

        // Verificação de segurança para evitar agendamentos duplicados
        $existingAppointment = Appointment::where('start_at', $startAt)
            ->where('service_id', $service->id)
            ->exists();

        if ($existingAppointment) {
            return response()->json(['error' => 'Desculpe, este horário acabou de ser preenchido. Por favor, escolha outro.'], 409);
        }

        Appointment::create([
            'service_id' => $service->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => 'confirmed',
        ]);

        return response()->json(['success' => 'Agendamento confirmado com sucesso!']);
    }
}