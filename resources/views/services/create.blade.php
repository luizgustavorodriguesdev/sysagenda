{{-- resources/views/services/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Adicionar Novo Serviço') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('service.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nome do Serviço')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="duration_minutes" :value="__('Duração (em minutos)')" />
                            <x-text-input id="duration_minutes" class="block mt-1 w-full" type="number" name="duration_minutes" :value="old('duration_minutes')" required />
                            <x-input-error :messages="$errors->get('duration_minutes')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Preço (R$)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" name="price" :value="old('price')" required step="0.01" />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('service.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Salvar Serviço') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>