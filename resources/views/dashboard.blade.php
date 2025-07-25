{{-- resources/views/dashboard.blade.php --}}

<div class="p-6 text-gray-900 dark:text-gray-100">
    {{-- Verifica se a coleção de negócios do usuário NÃO está vazia --}}
    @if(auth()->user()->businesses->isNotEmpty())

        <p class="text-lg">Seu negócio:</p>
        <p class="text-2xl font-bold">{{ auth()->user()->businesses->first()->name }}</p>
        {{-- Em breve, aqui teremos o painel de gerenciamento --}}

    @else

        <h2 class="text-xl font-semibold mb-4">Bem-vindo ao SysAgenda!</h2>
        <p class="mb-6">
            Você ainda não cadastrou um negócio. Crie um agora para começar a gerenciar seus serviços e agendamentos.
        </p>
        <a href="{{ route('business.create') }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
            Criar meu Negócio
        </a>

    @endif
</div>