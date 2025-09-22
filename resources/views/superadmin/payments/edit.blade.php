<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Registo de Pagamento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('superadmin.payments.update', $payment) }}">
                        @csrf
                        @method('PUT')

                        {{-- Detalhes do Pagamento --}}
                        <div class="mb-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400">A editar pagamento para: <span class="font-semibold">{{ $payment->user->name }}</span></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Plano associado: <span class="font-semibold">{{ $payment->plan->name }}</span></p>
                        </div>

                        {{-- Campos do formulário --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="amount" :value="__('Valor Pago (R$)')" />
                                <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount', $payment->amount)" required />
                            </div>

                            <div>
                                <x-input-label for="payment_date" :value="__('Data do Pagamento')" />
                                <x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" :value="old('payment_date', $payment->payment_date->format('Y-m-d'))" required />
                            </div>

                            <div>
                                <x-input-label for="payment_type" :value="__('Tipo de Pagamento')" />
                                <select name="payment_type" id="payment_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="pix" @if(old('payment_type', $payment->payment_type) == 'pix') selected @endif>Pix</option>
                                    <option value="transferencia" @if(old('payment_type', $payment->payment_type) == 'transferencia') selected @endif>Transferência</option>
                                    <option value="dinheiro" @if(old('payment_type', $payment->payment_type) == 'dinheiro') selected @endif>Dinheiro</option>
                                    <option value="outro" @if(old('payment_type', $payment->payment_type) == 'outro') selected @endif>Outro</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status do Pagamento')" />
                                <select name="status" id="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="active" @if(old('status', $payment->status) == 'active') selected @endif>Ativo</option>
                                    <option value="inactive" @if(old('status', $payment->status) == 'inactive') selected @endif>Inativo (Ex: Estornado)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notas (Opcional)')" />
                            <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $payment->notes) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('superadmin.clients.edit', $payment->user_id) }}" class="text-sm text-gray-600 dark:text-gray-400">Cancelar</a>
                            <x-primary-button class="ms-4">{{ __('Atualizar Pagamento') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>