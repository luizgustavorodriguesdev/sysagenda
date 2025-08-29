<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Importe a classe Str


class BarberController extends Controller
{
    /**
     * Exibir uma listagem do recurso.
     */
    public function index()
    {
        // Pega o negócio do usuário autenticado
        $business = auth()->user()->businesses()->first();

        // pega os barbeiros que pertencem a esse negócio
        // se não houver negócio, retorna uma coleção vazia
        $barbers = $business ? $business->barbers()->get() : collect();

        // Retorna a view com os barbeiros
        return view('barbers.index', compact('barbers'));
    }

    /**
     * Mostra o formulário para criar um novo barbeiro, mas antes verifica o limite do plano.
     */
    public function create()
    {
        $this->authorize('create', Barber::class);

        $userPlan = auth()->user()->plan;

        // Se o utilizador não tiver um plano...
        if (!$userPlan) {
            // A CORREÇÃO ESTÁ AQUI:
            // Garantimos que o redirecionamento aponta para a rota 'subscribe.index'.
            return redirect()->route('subscribe.index')->with('error', 'Você precisa de assinar um plano para adicionar barbeiros.');
        }

        $business = auth()->user()->businesses()->first();
        $currentBarberCount = $business->barbers()->count();

        // Se o limite de barbeiros do plano foi atingido...
        if ($currentBarberCount >= $userPlan->barber_limit) {
            // Aqui, o redirecionamento de volta para 'barbers.index' está correto,
            // pois o utilizador já está no fluxo de gestão de barbeiros.
            return redirect()->route('barbers.index')->with('error', 'Você atingiu o limite de barbeiros do seu plano. Faça um upgrade para adicionar mais.');
        }

        // Se passou em todas as verificações, mostra o formulário de criação.
        return view('barbers.create');
    }

    /**
     * Guarda um novo barbeiro, mas antes faz uma última verificação do limite.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Barber::class);

        // Repetimos a mesma verificação aqui como uma camada extra de segurança.
        $business = $request->user()->businesses()->first();
        $userPlan = $request->user()->plan;

        if (!$userPlan) {
            // ANTES: redirecionava para barbers.index
            // AGORA: redireciona para a página de escolha de plano
            return redirect()->route('subscribe.index')->with('error', 'Você precisa de assinar um plano para adicionar barbeiros.');
        }

        if (!$userPlan || $business->barbers()->count() >= $userPlan->barber_limit) {
            // Se, por alguma razão, o utilizador contornou o primeiro bloqueio,
            // ele é bloqueado aqui antes de salvar na base de dados.
            return back()->with('error', 'Não foi possível adicionar o barbeiro. Limite do plano atingido.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $business->barbers()->create($validated);
        return redirect()->route('barbers.index')->with('success', 'Barbeiro adicionado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         // Pergunta à BarberPolicy: "O utilizador atual pode 'update' este $barber específico?"
        $this->authorize('update', $barber);
        return view('barbers.edit', compact('barber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Pergunta à BarberPolicy: "O utilizador atual pode 'update' este $barber específico?"
        $this->authorize('update', $barber);
        $validated = $request->validate([/*...*/]);
        $barber->update($validated);
        return redirect()->route('barbers.index')->with('success', 'Barbeiro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Pergunta à BarberPolicy: "O utilizador atual pode 'delete' este $barber específico?"
        $this->authorize('delete', $barber);
        $barber->delete();
        return redirect()->route('barbers.index')->with('success', 'Barbeiro apagado com sucesso!');
    }
}
