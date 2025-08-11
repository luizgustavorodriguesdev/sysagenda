<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmed;

class AppointmentController extends Controller
{
    /**
     * Exibe uma lista de agendamentos para o negócio do usuário autenticado.
     */
    public function index()
    {
        $business = auth()->user()->businesses()->first();

        if (!$business) {
            return view('appointments.index', ['appointments' => collect()]);
        }

        $serviceIds = $business->services()->pluck('id');

        $appointments = Appointment::whereIn('service_id', $serviceIds)
                                    ->whereDate('start_at', '>=', now())
                                    ->with('service')
                                    ->orderBy('start_at', 'asc')
                                    ->get();

        return view('appointments.index', compact('appointments'));
    }
    
    public function storeBooking(Request $request)
    {
        // ... (toda a lógica de validação e verificação que já existe) ...
        $validated = $request->validate([/* ... */]);
        $service = Service::find($validated['service_id']);
        $startAt = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['time']);
        // ... (lógica para encontrar o $availableBarber) ...

        if (!$availableBarber) {
            return response()->json(['error' => 'Desculpe, este horário acabou de ser preenchido. Por favor, escolha outro.'], 409);
        }

        // Apanha a instância do novo agendamento que foi criado
        $newAppointment = Appointment::create([
            'service_id' => $service->id,
            'barber_id' => $availableBarber->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'start_at' => $startAt,
            'end_at' => $startAt->copy()->addMinutes($service->duration_minutes),
            'status' => 'confirmed',
        ]);

        // !! A NOVA PARTE !!
        // Dispara o e-mail para o cliente.
        Mail::to($newAppointment->customer_email)->send(new AppointmentConfirmed($newAppointment));

        return response()->json(['success' => 'Agendamento confirmado com sucesso!']);
    }
}
