<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Mostra a lista de todos os planos.
     */
    public function index()
    {
        $plans = Plan::WithCount('users')->get();// withCount('users') conta quantos utilizadores estão em cada plano
        return view('superadmin.plans.index', compact('plans'));
    }

    /**
     * Mostra o formulário para criar um novo plano.
     */
    public function create()
    {
        return view('superadmin.plans.create');
    }

    /**
     * Guarda o novo plano na base de dados.
     */
    public function store(Request $request)
    {
        $validated  = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans',
            'price' => 'required|numeric|min:0',
            'barber_limit' => 'required|integer|min:0',
            'description' => 'required|string',
        ]);

        // Deixamos o stripe_plan_id em branco por agora
        $validated['stripe_plan_id'] = 'manual_' . $validated['slug'];

        Plan::create($validated);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plano criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Mostra o formulário para editar um plano existente.
     */
    public function edit(Plan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }    

    /**
     * Atualiza o plano na base de dados.
     */
    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . $plan->id,
            'price' => 'required|numeric|min:0',
            'barber_limit' => 'required|integer|min:0',
            'description' => 'required|string',
        ]);

        $plan->update($validated);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plano atualizado com sucesso!');
    }

    /**
     * Apaga um plano da base de dados, com verificação de segurança.
     */
    public function destroy(Plan $plan)
    {
       // REGRA DE NEGÓCIO: Verifica se o plano tem utilizadores associados.
        if ($plan->users()->count() > 0) {
            // Se tiver, redireciona de volta com uma mensagem de erro.
            return redirect()->route('superadmin.plans.index')->with('error', 'Não é possível apagar este plano, pois existem clientes ativos a usá-lo.');
        }

        // Se não tiver, apaga o plano.
        $plan->delete();

        return redirect()->route('superadmin.plans.index')->with('success', 'Plano apagado com sucesso!');
    }
}
