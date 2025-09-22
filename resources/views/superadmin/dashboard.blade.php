<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel Super Admin - Gestão de Clientes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 flex space-x-4">                
                <a href="{{ route('superadmin.plans.index') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                    Gerir Planos
                </a>               
                
                <a href="{{ route('superadmin.clients.create') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                    + Adicionar Novo Cliente
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Lista de Clientes</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plano</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status do Teste</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Assinatura</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($clients as $client)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $client->plan->name ?? 'Nenhum' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($client->isOnTrial())
                                            <span class="text-green-600">Ativo, termina em {{ $client->trial_ends_at->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-red-600">Expirado ou inativo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($client->isSubscriptionActive())
                                            <span class="text-green-600">Ativa, termina em {{ $client->subscription_ends_at->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-red-600">Inativa ou expirada</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <a href="{{ route('superadmin.clients.edit', $client) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Gerir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">Nenhum cliente (admin) encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>