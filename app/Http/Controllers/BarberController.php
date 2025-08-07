<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * Mostrar o formulário para criação de um novo barbeiro.
     */
    public function create()
    {
        return view('barbers.create');
    }

    /**
     * Guarda um novo barbeiro na base de dados.
     */
    public function store(Request $request)
    {
        //validação dos dados do formulario
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        // pega o negocio do utilizador autenticado
        $business = auth()->user()->businesses()->first();

        //Cria o barbeiro e associa ao negócio
        $business->barbers()->create($validated);
        
        //redireciona para volta para a lista de barbeiros com mensagem de sucesso
        return redirect()->route('barbers.index')->with('success', 'Barbeiro criado com sucesso!');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
