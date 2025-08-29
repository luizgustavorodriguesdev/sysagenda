<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gerir Cliente: {{ $user->name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('superadmin.clients.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="plan_id" value="Plano do Cliente" />
                            <select name="plan_id" id="plan_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}" @if($user->plan_id == $plan->id) selected @endif>
                                        {{ $plan->name }} (Até {{ $plan->barber_limit }} barbeiros)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-4">
                            <x-input-label for="subscription_ends_at" value="Assinatura válida até (deixe em branco se inativa)" />
                            <x-text-input id="subscription_ends_at" class="block mt-1 w-full" type="date" name="subscription_ends_at" :value="old('subscription_ends_at', $user->subscription_ends_at?->format('Y-m-d'))" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>{{ __('Atualizar Cliente') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>