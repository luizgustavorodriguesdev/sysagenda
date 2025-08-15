<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Barber;
use Carbon\Carbon;
use Illuminate\Http\Request;
// Removidos os 'use' de Mail por agora
// use Illuminate\Support\Facades\Mail;
// use App\Mail\AppointmentConfirmed;

class PublicBookingController extends Controller
{
    // ... (os métodos show() e getAvailability() continuam iguais)
    public function show(Business $business)
    {
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

        if ($barberId) {
            $barbers = Barber::where('id', $barberId)->get();
        } else {
            $barbers = $service->barbers()->get();
        }

        $allAvailableSlots = [];
        foreach ($barbers as $barber) {
            $schedule = $barber->schedules()->where('day_of_week', $dayOfWeek)->first();
            if (!$schedule) {
                continue;
            }

            $serviceDuration = $service->duration_minutes;
            $startTime = Carbon::parse($schedule->start_time);
            $endTime = Carbon::parse($schedule->end_time);
            $barberSlots = [];
            while ($startTime < $endTime) {
                $barberSlots[] = $startTime->format('H:i');
                $startTime->addMinutes($serviceDuration);
            }

            // ================== A CORREÇÃO ESTÁ AQUI ==================
            // Agora só buscamos os agendamentos que estão com o status 'confirmed'.
            $existingAppointments = $barber->appointments()
                ->where('status', 'confirmed')
                ->whereDate('start_at', $bookingDate)
                ->get()
                ->pluck('start_at');
            // =========================================================

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
     * Salva um novo agendamento com validação robusta.
     */
    public function storeBooking(Request $request)
    {
        // ================== CORREÇÃO DE VALIDAÇÃO ==================
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'nullable|exists:barbers,id',
            // Adiciona a regra 'date' e 'after_or_equal:today' para segurança no servidor.
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);
        // ==========================================================

        $service = Service::find($validated['service_id']);
        $startAt = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);
        $endAt = $startAt->copy()->addMinutes($service->duration_minutes);
        $availableBarber = null;

        $barberPool = !empty($validated['barber_id'])
            ? Barber::where('id', $validated['barber_id'])->get()
            : $service->barbers;

        foreach ($barberPool as $barber) {
            // ================== CORREÇÃO DE SOBREPOSIÇÃO ==================
            // Procura por qualquer agendamento para este barbeiro que se sobreponha
            // com o novo horário de agendamento.
            $isOverlapping = $barber->appointments()
                ->where('start_at', '<', $endAt)
                ->where('end_at', '>', $startAt)
                ->exists();
            // =============================================================

            if (!$isOverlapping) {
                $availableBarber = $barber;
                break;
            }
        }

        if (!$availableBarber) {
            return response()->json(['error' => 'Desculpe, este horário acabou de ser preenchido ou está indisponível. Por favor, escolha outro.'], 409);
        }

        $appointmentData = [
            'service_id' => $service->id,
            'barber_id' => $availableBarber->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => 'confirmed',
        ];

        if (auth()->check() && auth()->user()->isClient()) {
            $appointmentData['user_id'] = auth()->id();
        }

        Appointment::create($appointmentData);

        // O envio de e-mail continua desativado por agora.
        // Mail::to(...)->send(...);

        return response()->json(['success' => 'Agendamento confirmado com sucesso!']);
    }
}