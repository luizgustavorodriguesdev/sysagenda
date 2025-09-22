<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Adicionar Novo Cliente (Dono de Negócio)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('superadmin.clients.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Nome do Cliente')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Senha Inicial')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('superadmin.dashboard') }}" class="text-sm text-gray-600">Cancelar</a>
                            <x-primary-button class="ms-3">{{ __('Criar Cliente') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>