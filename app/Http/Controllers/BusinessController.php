<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Importe a classe Str

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Exibe o formulário para criar um novo negocio.
     */
    public function create()
    {
        // Apenas retorna a view que acabamos de criar
        return view('business.create');
    }

    /**
     * Armazene um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        // 1. Validar os dados do formulário
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'branch' => 'required|string|max:100',
        ]);

        // 2. Obter o usuário autenticado
        $user = $request->user();

        // 3. Criar o negócio associado ao usuário
        $user->businesses()->create([
            'name' => $validated['name'],
            'branch' => $validated['branch'],
            'slug' => Str::slug($validated['name']), // Gera um slug a partir do nome
        ]);

        // 4. Redirecionar de volta para o dashboard com uma mensagem de sucesso
        return redirect()->route('dashboard')->with('success', 'Negócio criado com sucesso!');
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
