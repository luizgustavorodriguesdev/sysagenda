<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Importe o Hash
use Illuminate\Validation\Rules; // Importe as Regras de Validação


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
    // Em app/Http/Controllers/SuperAdmin/DashboardController.php

    public function editClient(User $user)
    {
        // Carrega as relações para podermos aceder a $user->payments na view
        $user->load(['plan', 'payments.plan']);
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

    // NOVO MÉTODO PARA MOSTRAR O FORMULÁRIO DE CRIAÇÃO
    public function createClient()
    {
        return view('superadmin.create-client');
    }

     // NOVO MÉTODO PARA SALVAR O NOVO CLIENTE
    public function storeClient(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_ADMIN, // Define a role como 'admin'
            'trial_ends_at' => now()->addDays(15), // Inicia o período de teste de 15 dias
        ]);

        return redirect()->route('superadmin.dashboard')->with('success', 'Novo cliente criado e período de teste iniciado!');
    }


}