{{-- resources/views/appointments/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meus Agendamentos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serviço</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                {{-- NOVA COLUNA DE CABEÇALHO --}}
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($appointments as $appointment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->start_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->start_at->format('H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->customer_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->service->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    {{-- CÉLULA COM O BOTÃO DE CANCELAMENTO --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Tem a certeza de que quer cancelar este agendamento?')">
                                                Cancelar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Agora o colspan é 6 --}}
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Nenhum agendamento futuro encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>                    
                    @if (session('success'))
                        <div class="mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>