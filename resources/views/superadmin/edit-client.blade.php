<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gerir Cliente: {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Bloco para exibir mensagens de sucesso --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Formulário para Registar Novo Pagamento e Ativar/Atualizar Assinatura --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Registar Novo Pagamento</h3>
                    <form method="POST" action="{{ route('superadmin.clients.payments.store', $user) }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {{-- Selecionar Plano --}}
                            <div>
                                <x-input-label for="plan_id" value="Plano Pago" />
                                <select name="plan_id" id="plan_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}" @if($user->plan_id == $plan->id) selected @endif>{{ $plan->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Valor Pago --}}
                            <div>
                                <x-input-label for="amount" value="Valor Pago (R$)" />
                                <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" required />
                            </div>

                            {{-- Tipo de Pagamento --}}
                            <div>
                                <x-input-label for="payment_type" value="Tipo de Pagamento" />
                                <select name="payment_type" id="payment_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="pix">Pix</option>
                                    <option value="transferencia">Transferência</option>
                                    <option value="dinheiro">Dinheiro</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>

                            {{-- Data do Pagamento --}}
                            <div>
                                <x-input-label for="payment_date" value="Data do Pagamento" />
                                <x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" required :value="now()->format('Y-m-d')" />
                            </div>

                            {{-- Nova Data de Fim da Assinatura --}}
                            <div>
                                <x-input-label for="new_subscription_ends_at" value="Nova Validade da Assinatura" />
                                <x-text-input id="new_subscription_ends_at" name="new_subscription_ends_at" type="date" class="mt-1 block w-full" required />
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div class="mt-4">
                            <x-input-label for="notes" value="Notas (Opcional)" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>{{ __('Registar Pagamento e Atualizar Assinatura') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Histórico de Pagamentos --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Histórico de Pagamentos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Data Pag.</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Plano</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Valor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nova Validade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($user->payments as $payment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($payment->payment_type) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->plan->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->new_subscription_ends_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($payment->status == 'active')
                                                <span class="text-green-600">Ativo</span>
                                            @else
                                                <span class="text-red-600">Inativo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <a href="{{ route('superadmin.payments.edit', $payment) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                            <form action="{{ route('superadmin.payments.destroy', $payment) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Tem a certeza?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Apagar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Nenhum pagamento registado para este cliente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>