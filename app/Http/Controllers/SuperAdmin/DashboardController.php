<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = User::where('role', User::ROLE_ADMIN)->with('plan')->get();
        return view('superadmin.dashboard', compact('clients'));
    }

    /**
     * Mostra o formulário para editar os dados de um cliente (utilizador admin).
     */
    public function editClient(User $user) // <-- A CORREÇÃO ESTÁ AQUI
    {
        // A assinatura do método PRECISA de ter (User $user) para que o Laravel
        // injete automaticamente o cliente que está a ser editado a partir da URL.
        
        $plans = Plan::all();
        return view('superadmin.edit-client', compact('user', 'plans'));
    }

    /**
     * Atualiza os dados do cliente (utilizador admin).
     */
    public function updateClient(Request $request, User $user)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'subscription_ends_at' => 'nullable|date',
        ]);

        $user->update($validated);

        return redirect()->route('superadmin.dashboard')->with('success', 'Cliente atualizado com sucesso!');
    }
}