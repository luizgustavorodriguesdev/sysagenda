<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl leading-tight">Editar Registo de Pagamento</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('superadmin.payments.update', $payment) }}">
                        @csrf
                        @method('PUT')
                        {{-- Campos do formulário --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><x-input-label for="amount" value="Valor Pago (R$)" /><x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount', $payment->amount)" required /></div>
                            <div><x-input-label for="payment_date" value="Data do Pagamento" /><x-text-input id="payment_date" name="payment_date" type="date" class="mt-1 block w-full" :value="old('payment_date', $payment->payment_date->format('Y-m-d'))" required /></div>
                            <div>
                                <x-input-label for="payment_type" value="Tipo de Pagamento" />
                                <select name="payment_type" id="payment_type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="pix" @if($payment->payment_type == 'pix') selected @endif>Pix</option>
                                    <option value="transferencia" @if($payment->payment_type == 'transferencia') selected @endif>Transferência</option>
                                    <option value="dinheiro" @if($payment->payment_type == 'dinheiro') selected @endif>Dinheiro</option>
                                    <option value="outro" @if($payment->payment_type == 'outro') selected @endif>Outro</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="status" value="Status" />
                                <select name="status" id="status" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="active" @if($payment->status == 'active') selected @endif>Ativo</option>
                                    <option value="inactive" @if($payment->status == 'inactive') selected @endif>Inativo (Ex: Estornado)</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4"><x-input-label for="notes" value="Notas (Opcional)" /><textarea id="notes" name="notes" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $payment->notes) }}</textarea></div>
                        <div class="flex items-center justify-end mt-4"><x-primary-button>{{ __('Atualizar Pagamento') }}</x-primary-button></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>