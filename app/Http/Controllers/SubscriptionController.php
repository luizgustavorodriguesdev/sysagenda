<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Mostra a página de planos para o utilizador escolher.
     */
    public function index()
    {
        // Busca todos os planos da nossa base de dados
        $plans = Plan::all();
        return view('subscribe.index', compact('plans'));
    }

    /**
     * Processa a escolha do plano e redireciona o utilizador para o checkout do Stripe.
     */
    public function store(Request $request)
    {
        // Valida se o plano escolhido existe
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $plan = Plan::find($validated['plan_id']);
        $user = $request->user();

        // Adicionamos '->withCustomerOptions()' para passar o ID do utilizador para o Stripe.
        return $user->newSubscription('default', $plan->stripe_plan_id)
                //->withCustomerOptions(['client_reference_id' => $user->id]) 
                ->checkout([
                    'success_url' => route('dashboard'),
                    'cancel_url' => route('subscribe.index'),
                ]);
    }
}