<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Pega o ID do utilizador logado
        $userId = auth()->id();

        // Busca todos os agendamentos associados a este utilizador
        // Eager load (with) para carregar os dados relacionados de forma eficiente
        $appointments = \App\Models\Appointment::where('user_id', $userId)
            ->with(['service.business', 'barber'])
            ->latest('start_at') // Ordena pelos mais recentes primeiro
            ->get();

        return view('client.dashboard', compact('appointments'));
    }
}