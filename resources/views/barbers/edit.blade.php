{{-- resources/views/barbers/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Barbeiro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('barbers.update', $barber) }}">
                        @csrf
                        @method('PUT') {{-- Informa o Laravel que é uma atualização --}}

                        <div>
                            <x-input-label for="name" :value="__('Nome do Barbeiro')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $barber->name)" required autofocus />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email (Opcional)')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $barber->email)" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="phone" :value="__('Telefone (Opcional)')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $barber->phone)" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('barbers.index') }}" class="text-sm text-gray-600 dark:text-gray-400">Cancelar</a>
                            <x-primary-button class="ms-3">
                                {{ __('Atualizar Barbeiro') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>