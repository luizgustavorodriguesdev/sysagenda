<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Conta Expirada') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-center text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold text-red-600 mb-4">A sua assinatura ou período de teste expirou!</h3>
                    <p class="mb-6">
                        Para continuar a gerir o seu negócio e aceder a todas as funcionalidades, por favor, entre em contacto com o nosso suporte para ativar a sua assinatura.
                    </p>
                    {{-- Pode adicionar aqui um e-mail ou telefone de contacto --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>