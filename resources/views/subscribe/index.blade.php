{{-- resources/views/subscribe/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Escolha o seu Plano') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($plans as $plan)
                            <div class="border rounded-lg p-6 flex flex-col">
                                <h3 class="text-2xl font-bold text-center">{{ $plan->name }}</h3>
                                <p class="text-4xl font-bold text-center my-4">
                                    R$ {{ number_format($plan->price / 100, 2, ',', '.') }} <span class="text-lg font-normal">/mês</span>
                                </p>
                                <p class="text-gray-500 text-center flex-grow">{{ $plan->description }}</p>
                                <ul class="my-6 space-y-2">
                                    <li class="flex items-center">
                                        <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Até {{ $plan->barber_limit == 1 ? '1 barbeiro' : $plan->barber_limit . ' barbeiros' }}
                                    </li>
                                    {{-- Adicione mais características aqui --}}
                                </ul>

                                {{-- Formulário para escolher o plano --}}
                                <form action="{{ route('subscribe.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                    <x-primary-button class="w-full justify-center">
                                        Assinar Agora
                                    </x-primary-button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>