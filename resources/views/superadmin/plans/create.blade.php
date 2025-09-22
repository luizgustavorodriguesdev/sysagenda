<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl leading-tight">{{ __('Adicionar Novo Plano') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('superadmin.plans.store') }}">
                        @csrf
                        {{-- Inclua os campos do formulário aqui --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><x-input-label for="name" value="Nome do Plano" /><x-text-input id="name" name="name" class="block mt-1 w-full" type="text" required /></div>
                            <div><x-input-label for="slug" value="Slug (ex: plano-bronze)" /><x-text-input id="slug" name="slug" class="block mt-1 w-full" type="text" required /></div>
                            <div><x-input-label for="price" value="Preço (ex: 19.90)" /><x-text-input id="price" name="price" class="block mt-1 w-full" type="number" step="0.01" required /></div>
                            <div><x-input-label for="barber_limit" value="Limite de Barbeiros" /><x-text-input id="barber_limit" name="barber_limit" class="block mt-1 w-full" type="number" required /></div>
                        </div>
                        <div class="mt-4"><x-input-label for="description" value="Descrição" /><textarea id="description" name="description" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"></textarea></div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>{{ __('Salvar Plano') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>