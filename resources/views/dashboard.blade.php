<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Lógica Principal do Dashboard --}}

                    {{-- Verifica se a coleção de negócios do usuário NÃO está vazia --}}
                    @if(auth()->user()->businesses->isNotEmpty())

                        {{-- SE O USUÁRIO JÁ TEM UM NEGÓCIO --}}
                        <h2 class="text-lg font-semibold">Painel do seu negócio:</h2>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-4">
                            {{ auth()->user()->businesses->first()->name }}
                        </p>
                        
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-semibold mb-4">Gerenciar</h3>
                            <a href="{{ route('service.index') }}"
                            class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                            Gerenciar Serviços
                            </a>
                            <a href="{{ route('schedule.edit') }}"
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                            Definir Horários
                            </a>
                            <a href="{{ route('appointments.index') }}"
                            class="inline-block bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                            Ver Agendamentos
                            </a>
                        </div>
                    @else

                        {{-- SE O USUÁRIO AINDA NÃO TEM UM NEGÓCIO --}}
                        <h2 class="text-xl font-semibold mb-4">Bem-vindo ao SysAgenda!</h2>
                        <p class="mb-6">
                            Você ainda não cadastrou um negócio. Crie um agora para começar a gerenciar seus serviços e agendamentos.
                        </p>
                        <a href="{{ route('business.create') }}"
                           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                            Criar meu Negócio
                        </a>

                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>