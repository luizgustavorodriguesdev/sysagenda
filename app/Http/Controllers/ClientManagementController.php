<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ClientManagementController extends Controller
{
    public function index()
    {
        // Pega o negócio do dono logado.
        $business = auth()->user()->businesses()->first();

        // A CONSULTA INTELIGENTE:
        // 1. Começamos com todos os utilizadores que são clientes.
        $clients = User::where('role', User::ROLE_CLIENT)
            // 2. Usamos 'whereHas' para filtrar: "onde o utilizador TENHA agendamentos..."
            ->whereHas('appointments.service', function ($query) use ($business) {
                // 3. "...cujo serviço pertença a este negócio."
                $query->where('business_id', $business->id);
            })
            // 4. Pega os resultados.
            ->get();

        return view('client-management.index', compact('clients'));
    }
}
