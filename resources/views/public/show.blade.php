<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Meta Tag CSRF essencial para a segurança dos envios de formulário via JavaScript --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Agendamento em {{ $business->name }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans antialiased">
    <div class="container mx-auto p-4 md:p-8">
        <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8">
            <header class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800 dark:text-gray-200">{{ $business->name }}</h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">{{ $business->branch }}</p>
            </header>

            <main>
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Nossos Serviços</h2>

                @forelse ($services as $service)
                    {{-- Apenas mostra serviços que tenham pelo menos um barbeiro associado --}}
                    @if($service->barbers->isNotEmpty())
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-md mb-4" x-data="{ open: false }">
                            {{-- Cabeçalho do Serviço (clicável) --}}
                            <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-800 dark:text-gray-200">{{ $service->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Duração: {{ $service->duration_minutes }} minutos</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-lg text-green-600">R$ {{ number_format($service->price, 2, ',', '.') }}</p>
                                    <button class="mt-1 bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                        Agendar <span x-show="!open">▼</span><span x-show="open">▲</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Área de Agendamento (que abre e fecha com Alpine.js) --}}
                            <div x-show="open" x-transition class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">

                                {{-- PASSO 1: ESCOLHER O BARBEIRO --}}
                                <div class="mb-4">
                                    <label for="barber-picker-{{ $service->id }}" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">Escolha um profissional:</label>
                                    <select id="barber-picker-{{ $service->id }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm w-full" onchange="fetchAvailability({{ $service->id }})">
                                        <option value="">Qualquer profissional disponível</option>
                                        @foreach($service->barbers as $barber)
                                            <option value="{{ $barber->id }}">{{ $barber->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- PASSO 2: ESCOLHER A DATA --}}
                                <div class="mb-4">
                                     <label for="date-picker-{{ $service->id }}" class="block font-semibold mb-2 text-gray-700 dark:text-gray-300">Escolha uma data:</label>
                                    <input type="date" id="date-picker-{{ $service->id }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" min="{{ now()->format('Y-m-d') }}" onchange="fetchAvailability({{ $service->id }})">
                                </div>

                                {{-- PASSO 3: VER OS HORÁRIOS --}}
                                <div id="availability-slots-{{ $service->id }}" class="mt-4 grid grid-cols-3 sm:grid-cols-4 gap-2">
                                    {{-- Os horários disponíveis serão inseridos aqui pelo JavaScript --}}
                                </div>

                                {{-- FORMULÁRIO DE CONFIRMAÇÃO FINAL (inicialmente escondido) --}}
                                <div id="booking-form-{{ $service->id }}" class="hidden mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                                     <form>
                                        <h4 class="font-semibold text-lg mb-2 text-gray-800 dark:text-gray-200">Confirmar Agendamento</h4>
                                        <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                                            <p>Serviço: <span class="font-bold">{{ $service->name }}</span></p>
                                            <p>Profissional: <span id="selected-barber-text-{{ $service->id }}" class="font-bold"></span></p>
                                            <p>Data e Hora: <span id="selected-slot-text-{{ $service->id }}" class="font-bold"></span></p>
                                        </div>

                                        <div class="mt-4">
                                            <label for="customer_name-{{ $service->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seu Nome</label>
                                            <input type="text" id="customer_name-{{ $service->id }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required>
                                        </div>
                                        <div class="mt-4">
                                            <label for="customer_email-{{ $service->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Seu E-mail</label>
                                            <input type="email" id="customer_email-{{ $service->id }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm" required>
                                        </div>
                                        <div class="mt-4 text-right">
                                            <button type="button" onclick="submitBooking({{ $service->id }})" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400">Nenhum serviço disponível no momento.</p>
                @endforelse
            </main>
        </div>
    </div>

    {{-- BLOCO DE JAVASCRIPT --}}
    <script>
        // Função assíncrona para buscar a disponibilidade na nossa API
        async function fetchAvailability(serviceId) {
            const datePicker = document.getElementById(`date-picker-${serviceId}`);
            const barberPicker = document.getElementById(`barber-picker-${serviceId}`);
            const selectedDate = datePicker.value;
            const selectedBarberId = barberPicker.value;

            const slotsContainer = document.getElementById(`availability-slots-${serviceId}`);
            // Esconde o formulário final sempre que uma nova data/barbeiro é selecionado
            document.getElementById(`booking-form-${serviceId}`).classList.add('hidden');

            // Se não houver data selecionada, não faz nada
            if (!selectedDate) {
                slotsContainer.innerHTML = '';
                return;
            }

            slotsContainer.innerHTML = '<p class="text-gray-500 dark:text-gray-400">Verificando horários...</p>';

            try {
                // Constrói a URL da API
                let apiUrl = `/api/availability/service/${serviceId}/date/${selectedDate}`;
                // Se um barbeiro específico foi selecionado, adiciona como um parâmetro query
                if (selectedBarberId) {
                    apiUrl += `?barber_id=${selectedBarberId}`;
                }

                const response = await fetch(apiUrl);
                if (!response.ok) throw new Error(`Erro HTTP! Status: ${response.status}`);
                const data = await response.json();

                slotsContainer.innerHTML = '';
                // Se a API retornou horários, cria os botões
                if (data.available_slots && data.available_slots.length > 0) {
                    data.available_slots.forEach(slot => {
                        const button = document.createElement('button');
                        button.className = 'bg-gray-200 dark:bg-gray-600 hover:bg-indigo-600 hover:text-white dark:hover:text-white text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded';
                        button.textContent = slot;
                        button.onclick = () => showBookingForm(serviceId, selectedDate, slot);
                        slotsContainer.appendChild(button);
                    });
                } else {
                    // Se não, mostra a mensagem
                    slotsContainer.innerHTML = '<p class="text-red-500">Nenhum horário disponível para esta seleção.</p>';
                }
            } catch (error) {
                console.error('Erro ao buscar disponibilidade:', error);
                slotsContainer.innerHTML = '<p class="text-red-500">Não foi possível verificar os horários. Tente novamente.</p>';
            }
        }

        // Função para mostrar o formulário de confirmação final
        function showBookingForm(serviceId, date, slot) {
            const formContainer = document.getElementById(`booking-form-${serviceId}`);
            const selectedSlotText = document.getElementById(`selected-slot-text-${serviceId}`);
            const selectedBarberText = document.getElementById(`selected-barber-text-${serviceId}`);
            const barberPicker = document.getElementById(`barber-picker-${serviceId}`);

            // Formata a data para um formato legível (ex: 08/08/2025)
            const formattedDate = new Date(date + 'T00:00:00').toLocaleDateString('pt-BR');

            // Pega o nome do barbeiro selecionado
            let barberName = "Qualquer profissional disponível";
            if (barberPicker.value) {
                barberName = barberPicker.options[barberPicker.selectedIndex].text;
            }

            // Preenche os detalhes no formulário de confirmação
            selectedBarberText.textContent = barberName;
            selectedSlotText.textContent = `${formattedDate} às ${slot}`;
            // Mostra o formulário
            formContainer.classList.remove('hidden');
        }

        // Função assíncrona para submeter o agendamento final
        async function submitBooking(serviceId) {
            // Pega os dados dos campos do formulário
            const customerName = document.getElementById(`customer_name-${serviceId}`).value;
            const customerEmail = document.getElementById(`customer_email-${serviceId}`).value;
            const barberId = document.getElementById(`barber-picker-${serviceId}`).value;
            const date = document.getElementById(`date-picker-${serviceId}`).value;
            const time = document.getElementById(`selected-slot-text-${serviceId}`).textContent.split(' às ')[1];

            // Pega o token CSRF da meta tag no <head>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const bookingData = {
                service_id: serviceId,
                barber_id: barberId,
                date: date,
                time: time,
                customer_name: customerName,
                customer_email: customerEmail,
            };

            try {
                const response = await fetch('/booking/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify(bookingData)
                });

                const result = await response.json();

                if (response.ok) {
                    // Se o agendamento foi bem-sucedido, mostra a mensagem de sucesso
                    const formContainer = document.getElementById(`booking-form-${serviceId}`);
                    formContainer.innerHTML = `<div class="text-center p-4 bg-green-100 text-green-700 rounded-lg">
                                                    <h4 class="font-bold text-lg">Obrigado!</h4>
                                                    <p>O seu agendamento para ${time} foi confirmado com sucesso.</p>
                                               </div>`;
                    // Esconde os seletores de horário
                    document.getElementById(`availability-slots-${serviceId}`).innerHTML = '';
                } else {
                    // Se houve um erro (ex: horário já preenchido), mostra um alerta
                    alert(result.error || 'Ocorreu um erro ao tentar confirmar o seu agendamento.');
                }
            } catch (error) {
                console.error('Erro ao submeter o agendamento:', error);
                alert('Ocorreu um erro de comunicação. Por favor, tente novamente.');
            }
        }
    </script>
</body>
</html>