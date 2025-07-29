<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // 1. Pega o negócio do usuário logado
        $business = auth()->user()->businesses->first();

        // 2. Pega os serviços pertencentes a esse negócio
        $services = $business ? $business->services()->get() : collect();

        // 3. Retorna a view com a lista de serviços
        return view('services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         // Apenas retorna a view do formulário de criação
        return view('services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // 2. Pega o negócio do usuário logado
        $business = $request->user()->businesses->first();

        // 3. Cria o serviço associado a esse negócio
        $business->services()->create($validated);

        // 4. Redireciona de volta para a lista de serviços com uma mensagem de sucesso
        return redirect()->route('service.index')->with('success', 'Serviço adicionado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        // O Laravel automaticamente encontra o serviço pelo ID na URL (Route-Model Binding)
        return view('services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        // 1. Validação dos dados
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        // 2. Atualiza o serviço com os dados validados
        $service->update($validated);

        // 3. Redireciona de volta para a lista com uma mensagem de sucesso
        return redirect()->route('service.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        // 1. Opcional, mas recomendado: Adicionar uma verificação de autorização.
        //    Garante que o usuário só pode excluir serviços do seu próprio negócio.
        //    (Vamos adicionar uma lógica mais robusta para isso no futuro, por enquanto está ok)

        // 2. Exclui o serviço do banco de dados
        $service->delete();

        // 3. Redireciona de volta para a lista com uma mensagem de sucesso
        return redirect()->route('service.index')->with('success', 'Serviço excluído com sucesso!');
    }
}
