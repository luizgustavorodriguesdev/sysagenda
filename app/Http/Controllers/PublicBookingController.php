<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Barber;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PublicBookingController extends Controller
{
    /**
     * Exibe a página pública de um negócio com seus serviços.
     */
    public function show(Business $business)
    {
        // Carrega os serviços e os barbeiros associados de uma só vez para melhor performance.
        $services = $business->services()->with('barbers')->get();
        return view('public.show', compact('business', 'services'));
    }

    /**
     * Calcula e retorna os horários disponíveis.
     */
    public function getAvailability(Request $request, Service $service, $date)
    {
        $bookingDate = Carbon::parse($date)->startOfDay();
        $dayOfWeek = $bookingDate->dayOfWeek;
        $barberId = $request->query('barber_id');

        // Se um barbeiro específico foi selecionado, busca apenas esse.
        if ($barberId) {
            $barbers = Barber::where('id', $barberId)->get();
        } else {
            // Se não, busca todos os barbeiros que fazem este serviço.
            $barbers = $service->barbers()->get();
        }

        $allAvailableSlots = [];
        foreach ($barbers as $barber) {
            // ================== VERIFICAÇÃO CRUCIAL ==================
            // Procura o horário de trabalho para ESTE barbeiro e para ESTE dia da semana.
            $schedule = $barber->schedules()->where('day_of_week', $dayOfWeek)->first();

            // Se não encontrou um horário ($schedule é null), significa que o barbeiro
            // NÃO trabalha neste dia. O 'continue' pula para o próximo barbeiro do loop.
            if (!$schedule) {
                continue;
            }
            // =========================================================

            // A partir daqui, o código só é executado se o barbeiro TRABALHA no dia selecionado.
            $serviceDuration = $service->duration_minutes;
            $startTime = Carbon::parse($schedule->start_time);
            $endTime = Carbon::parse($schedule->end_time);
            $barberSlots = [];
            while ($startTime < $endTime) {
                $barberSlots[] = $startTime->format('H:i');
                $startTime->addMinutes($serviceDuration);
            }

            $existingAppointments = $barber->appointments()
                ->whereDate('start_at', $bookingDate)
                ->get()
                ->pluck('start_at');

            $bookedSlots = $existingAppointments->map(function ($appointmentTime) {
                return Carbon::parse($appointmentTime)->format('H:i');
            })->toArray();

            $barberAvailableSlots = array_diff($barberSlots, $bookedSlots);
            $allAvailableSlots = array_merge($allAvailableSlots, $barberAvailableSlots);
        }

        $uniqueSlots = array_unique($allAvailableSlots);
        sort($uniqueSlots);

        return response()->json(['available_slots' => array_values($uniqueSlots)]);
    }

    /**
     * Salva um novo agendamento.
     */
    public function storeBooking(Request $request)
    {
        // (Este método permanece igual à versão anterior, que já está correta)
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:barbers,id',
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);

        $service = Service::find($validated['service_id']);
        $startAt = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);

        $isBooked = Appointment::where('barber_id', $validated['barber_id'])
                           ->where('start_at', $startAt)
                           ->exists();

        if ($isBooked) {
            return response()->json(['error' => 'Desculpe, este horário acabou de ser preenchido. Por favor, escolha outro.'], 409);
        }

        $endAt = $startAt->copy()->addMinutes($service->duration_minutes);
        Appointment::create([
            'service_id' => $service->id,
            'barber_id' => $validated['barber_id'],
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => 'confirmed',
        ]);

        return response()->json(['success' => 'Agendamento confirmado com sucesso!']);
    }
}