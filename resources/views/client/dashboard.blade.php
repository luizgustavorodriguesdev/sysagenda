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
                    <h3 class="text-lg font-semibold mb-4">Seu Histórico de Agendamentos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data & Hora</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profissional</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Serviço</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    {{-- NOVA COLUNA DE AÇÕES --}}
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->start_at->format('d/m/Y \à\s H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->barber->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->service->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- Lógica para mudar a cor do status --}}
                                            @if ($appointment->status == 'cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Cancelado
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Confirmado
                                                </span>
                                            @endif
                                        </td>
                                        {{-- CÉLULA COM O BOTÃO DE CANCELAMENTO CONDICIONAL --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- O botão só aparece se o agendamento for 'confirmado' E a data for no futuro --}}
                                            @if ($appointment->status == 'confirmed' && $appointment->start_at > now())
                                                <form action="{{ route('client.appointments.destroy', $appointment) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Tem a certeza de que quer cancelar este agendamento?')">
                                                        Cancelar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Você ainda não fez nenhum agendamento.
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
    </div>
</x-app-layout>