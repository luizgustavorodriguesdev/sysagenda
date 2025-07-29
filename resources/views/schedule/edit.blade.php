{{-- resources/views/schedule/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Definir Horários de Atendimento') }}
        </h2>
    </x-slot>

    
    {{-- mensagem sucesso --}}
    @if (session('success'))
        <div class="py-4">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Sucesso!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('schedule.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            @foreach ($daysOfWeek as $dayIndex => $dayName)
                                <div class="p-4 border rounded-lg dark:border-gray-600">
                                    <h3 class="font-semibold text-lg mb-3">{{ $dayName }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                        <div class="flex items-center space-x-2">
                                            <input type="checkbox" name="schedules[{{ $dayIndex }}][enabled]" id="enabled_{{ $dayIndex }}"
                                                   @if(isset($scheduleByDay[$dayIndex])) checked @endif>
                                            <label for="enabled_{{ $dayIndex }}">Trabalha neste dia</label>
                                        </div>

                                        <div>
                                            <label for="start_time_{{ $dayIndex }}" class="block text-sm font-medium">Início</label>
                                            <input type="time" name="schedules[{{ $dayIndex }}][start_time]" id="start_time_{{ $dayIndex }}"
                                                   value="{{ $scheduleByDay[$dayIndex]['start_time'] ?? '' }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                        </div>

                                        <div>
                                            <label for="end_time_{{ $dayIndex }}" class="block text-sm font-medium">Fim</label>
                                            <input type="time" name="schedules[{{ $dayIndex }}][end_time]" id="end_time_{{ $dayIndex }}"
                                                   value="{{ $scheduleByDay[$dayIndex]['end_time'] ?? '' }}"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-8">
                            <x-primary-button>
                                {{ __('Salvar Horários') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>