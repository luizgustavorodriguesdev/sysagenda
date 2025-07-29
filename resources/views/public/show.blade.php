{{-- resources/views/public/show.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agendamento em {{ $business->name }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="container mx-auto p-4 md:p-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-6 md:p-8">
            <header class="text-center mb-8">
                <h1 class="text-4xl font-bold text-gray-800">{{ $business->name }}</h1>
                <p class="text-lg text-gray-600">{{ $business->branch }}</p>
            </header>

            {{-- Em resources/views/public/show.blade.php --}}

            <main>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4 border-b pb-2">Nossos Serviços</h2>

                {{-- Loop através dos serviços --}}
                @forelse ($services as $service)
                    <div class="p-4 border rounded-md mb-4" x-data="{ open: false }">
                        {{-- Detalhes do Serviço --}}
                        <div class="flex justify-between items-center cursor-pointer" @click="open = !open">
                            <div>
                                <h3 class="font-bold text-lg">{{ $service->name }}</h3>
                                <p class="text-sm text-gray-500">Duração: {{ $service->duration_minutes }} minutos</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg text-green-600">R$ {{ number_format($service->price, 2, ',', '.') }}</p>
                                <button class="mt-1 bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                    Agendar <span x-show="!open">▼</span><span x-show="open">▲</span>
                                </button>
                            </div>
                        </div>

                        {{-- Área de Agendamento (que abre e fecha) --}}
                        <div x-show="open" x-transition class="mt-6 border-t pt-4">
                            <h4 class="font-semibold mb-2">Escolha uma data:</h4>
                            <input type="date"
                                id="date-picker-{{ $service->id }}"
                                class="rounded-md border-gray-300 shadow-sm"
                                min="{{ now()->format('Y-m-d') }}"
                                onchange="fetchAvailability({{ $service->id }})">

                            <div id="availability-slots-{{ $service->id }}" class="mt-4 grid grid-cols-4 gap-2">
                                {{-- Os horários disponíveis serão inseridos aqui pelo JavaScript --}}
                            </div>

                            {{-- O formulário de confirmação aparecerá aqui --}}
                            <div id="booking-form-{{ $service->id }}" class="hidden mt-4">
                                <form>
                                    <h4 class="font-semibold mb-2">Confirmar Agendamento</h4>
                                    <p>Serviço: <span class="font-bold">{{ $service->name }}</span></p>
                                    <p>Data e Hora: <span id="selected-slot-text-{{ $service->id }}" class="font-bold"></span></p>

                                    <div class="mt-4">
                                        <label for="customer_name-{{ $service->id }}" class="block text-sm font-medium">Seu Nome</label>
                                        <input type="text" id="customer_name-{{ $service->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    </div>
                                    <div class="mt-4">
                                        <label for="customer_email-{{ $service->id }}" class="block text-sm font-medium">Seu E-mail</label>
                                        <input type="email" id="customer_email-{{ $service->id }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    </div>
                                    <div class="mt-4 text-right">
                                        <!--<button type="button" class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg">Confirmar</button>-->

                                        <button type="button"
                                                onclick="submitBooking({{ $service->id }})"
                                                class="bg-green-600 text-white font-bold py-2 px-4 rounded-lg">
                                            Confirmar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500">Nenhum serviço disponível no momento.</p>
                @endforelse
            </main>
        </div>
    </div>


    <script>
        // Função para buscar a disponibilidade na nossa API
        async function fetchAvailability(serviceId) {
            const datePicker = document.getElementById(`date-picker-${serviceId}`);
            const selectedDate = datePicker.value;
            const slotsContainer = document.getElementById(`availability-slots-${serviceId}`);

            slotsContainer.innerHTML = '<p class="text-gray-500">Verificando horários...</p>';

            if (!selectedDate) {
                slotsContainer.innerHTML = '';
                return;
            }

            try {
                // **A CORREÇÃO ESTÁ AQUI**
                // Certifique-se de que a linha abaixo usa o ACENTO GRAVE ( ` ), não aspas simples ( ' ).
                const apiUrl = `/api/availability/service/${serviceId}/date/${selectedDate}`;

                const response = await fetch(apiUrl);

                // Adicionado para um melhor diagnóstico de erros
                if (!response.ok) {
                    throw new Error(`Erro HTTP! Status: ${response.status}`);
                }

                const data = await response.json();

                slotsContainer.innerHTML = '';

                if (data.available_slots && data.available_slots.length > 0) {
                    data.available_slots.forEach(slot => {
                        const button = document.createElement('button');
                        button.className = 'bg-gray-200 hover:bg-indigo-600 hover:text-white text-gray-800 font-bold py-2 px-4 rounded';
                        button.textContent = slot;
                        button.onclick = () => showBookingForm(serviceId, selectedDate, slot);
                        slotsContainer.appendChild(button);
                    });
                } else {
                    slotsContainer.innerHTML = '<p class="text-red-500">Nenhum horário disponível para esta data.</p>';
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
            const formattedDate = new Date(date + 'T00:00:00').toLocaleDateString('pt-BR');

            selectedSlotText.textContent = `${formattedDate} às ${slot}`;
            formContainer.classList.remove('hidden');
        }

        // Adicione esta função ao seu <script>

        async function submitBooking(serviceId) {
            // Pega os dados dos campos do formulário
            const customerName = document.getElementById(`customer_name-${serviceId}`).value;
            const customerEmail = document.getElementById(`customer_email-${serviceId}`).value;

            // Pega os dados que já tínhamos (data e hora)
            const date = document.getElementById(`date-picker-${serviceId}`).value;
            // Extrai a hora do texto "dd/mm/aaaa às HH:MM"
            const time = document.getElementById(`selected-slot-text-${serviceId}`).textContent.split(' às ')[1];

            // Pega o token CSRF da meta tag que adicionámos
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Monta o corpo da requisição
            const bookingData = {
                service_id: serviceId,
                date: date,
                time: time,
                customer_name: customerName,
                customer_email: customerEmail,
            };

            try {
                // Envia os dados para a nossa rota de salvamento usando um POST
                const response = await fetch('/booking/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // Envia o token CSRF no cabeçalho
                    },
                    body: JSON.stringify(bookingData) // Converte os dados para uma string JSON
                });

                const result = await response.json();

                if (response.ok) {
                    // Se o agendamento foi bem-sucedido
                    const formContainer = document.getElementById(`booking-form-${serviceId}`);
                    formContainer.innerHTML = `<div class="text-center p-4 bg-green-100 text-green-700 rounded-lg">
                                                    <h4 class="font-bold text-lg">Obrigado!</h4>
                                                    <p>O seu agendamento foi confirmado com sucesso.</p>
                                            </div>`;
                } else {
                    // Se houve um erro (ex: horário já preenchido)
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