<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

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
}