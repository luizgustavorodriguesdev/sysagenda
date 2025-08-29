<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Exibe a lista de serviços.
     */
    public function index()
    {
        $business = auth()->user()->businesses->first();
        $services = $business ? $business->services()->with('barbers')->get() : collect();
        return view('services.index', compact('services'));
    }

    /**
     * Mostra o formulário para criar um novo serviço.
     */
    public function create()
    {
        // A CORREÇÃO ESTÁ AQUI: Buscamos os barbeiros...
        $barbers = auth()->user()->businesses()->first()->barbers;

        // ...e enviámo-los para a view.
        return view('services.create', compact('barbers'));
    }

    /**
     * Guarda um novo serviço e as suas relações.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'barbers' => 'nullable|array',
            'barbers.*' => 'exists:barbers,id'
        ]);

        $business = $request->user()->businesses()->first();
        $service = $business->services()->create($validated);

        if (isset($validated['barbers'])) {
            $service->barbers()->sync($validated['barbers']);
        }

        return redirect()->route('service.index')->with('success', 'Serviço adicionado com sucesso!');
    }


    /**
     * Mostra o formulário para editar um serviço.
     */
    public function edit(Service $service)
    {
        $this->authorize('update', $service);
        // A CORREÇÃO ESTÁ AQUI: Buscamos todos os barbeiros...
        $barbers = auth()->user()->businesses()->first()->barbers;
        // ...e também os que já estão associados ao serviço.
        $associatedBarberIds = $service->barbers()->pluck('id')->toArray();

        // Enviamos tudo para a view.
        return view('services.edit', compact('service', 'barbers', 'associatedBarberIds'));
    }

    /**
     * Atualiza um serviço e as suas relações.
     */
    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'barbers' => 'nullable|array',
            'barbers.*' => 'exists:barbers,id'
        ]);

        $service->update($validated);
        $service->barbers()->sync($request->input('barbers', []));

        return redirect()->route('service.index')->with('success', 'Serviço atualizado com sucesso!');
    }

    /**
     * Apaga um serviço.
     */
    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);
        $service->delete();
        return redirect()->route('service.index')->with('success', 'Serviço apagado com sucesso!');
    }
}