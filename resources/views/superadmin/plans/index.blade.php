<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Gerir Planos') }}</h2>
            <a href="{{ route('superadmin.plans.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">+ Adicionar Novo Plano</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left">Nome</th>
                                <th class="px-6 py-3 text-left">Preço</th>
                                <th class="px-6 py-3 text-left">Limite Barbeiros</th>
                                <th class="px-6 py-3 text-left">Clientes Ativos</th>
                                <th class="px-6 py-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($plans as $plan)
                                <tr>
                                    <td class="px-6 py-4">{{ $plan->name }}</td>
                                    <td class="px-6 py-4">R$ {{ number_format($plan->price, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4">{{ $plan->barber_limit }}</td>
                                    <td class="px-6 py-4">{{ $plan->users_count }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('superadmin.plans.edit', $plan) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                        <form action="{{ route('superadmin.plans.destroy', $plan) }}" method="POST" class="inline ml-4">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem a certeza?')">Apagar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>