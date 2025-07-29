<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function edit()
    {
        // Pega o primeiro negócio do usuário autenticado
        $business = auth()->user()->businesses->first();

        // Se não houver negócio, redireciona para o dashboard.
        if (!$business) {
            return redirect()->route('dashboard');
        }

        // Busca todos os horários salvos para este negócio
        $schedules = $business->schedules()->get();

        // Organiza os horários em um array onde a chave é o dia da semana (0=Dom, 1=Seg...)
        $scheduleByDay = [];
        foreach ($schedules as $schedule) {
            $scheduleByDay[$schedule->day_of_week] = [
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ];
        }

        // Um array para ajudar a renderizar os dias na view
        $daysOfWeek = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];

        // Retorna a view, passando os dados necessários
        return view('schedule.edit', compact('scheduleByDay', 'daysOfWeek'));
    }

    /*
     * Atualiza os horários de atendimento no banco de dados.
     */
    /**
     * Atualiza os horários de atendimento no banco de dados.
     */
    public function update(Request $request)
    {
        // Pega o negócio do usuário que está logado.
        $business = $request->user()->businesses->first();

        // Pega todos os dados de horários enviados pelo formulário.
        // O resultado será um array, ex: [0 => ['enabled' => 'on', 'start_time' => '09:00', ...], 1 => ...]
        $schedulesData = $request->input('schedules', []);

        // Itera sobre cada dia da semana, de 0 (Domingo) a 6 (Sábado).
        foreach (range(0, 6) as $dayIndex) {

            // Verifica se o checkbox 'enabled' para este dia específico foi marcado no formulário.
            if (isset($schedulesData[$dayIndex]['enabled'])) {
                // SE O DIA FOI MARCADO COMO "TRABALHA NESTE DIA"

                // Validação para garantir que, se o dia está habilitado, os horários de início e fim foram preenchidos.
                $request->validate([
                    "schedules.{$dayIndex}.start_time" => 'required',
                    "schedules.{$dayIndex}.end_time" => 'required|after:schedules.'.$dayIndex.'.start_time',
                ], [
                    // Mensagens de erro personalizadas em português
                    "schedules.{$dayIndex}.start_time.required" => "O horário de início para o dia selecionado é obrigatório.",
                    "schedules.{$dayIndex}.end_time.required" => "O horário de fim para o dia selecionado é obrigatório.",
                    "schedules.{$dayIndex}.end_time.after" => "O horário de fim deve ser posterior ao horário de início.",
                ]);


                // A "mágica" acontece aqui com updateOrCreate.
                // Esta função tenta encontrar um registro que corresponda ao primeiro array (as condições).
                // Se encontrar, ele atualiza o registro com os dados do segundo array.
                // Se não encontrar, ele cria um novo registro com a junção dos dois arrays.
                $business->schedules()->updateOrCreate(
                    [
                        // Condições para encontrar o registro:
                        'day_of_week' => $dayIndex,
                    ],
                    [
                        // Valores para atualizar ou criar:
                        'start_time' => $schedulesData[$dayIndex]['start_time'],
                        'end_time' => $schedulesData[$dayIndex]['end_time'],
                    ]
                );

            } else {
                // SE O DIA FOI DESMARCADO

                // Procura por um horário existente para este dia e o exclui do banco de dados.
                $business->schedules()->where('day_of_week', $dayIndex)->delete();
            }
        }

        // Após salvar tudo, redireciona o usuário de volta para a mesma página de edição
        // com uma mensagem de sucesso para que ele veja o resultado.
        return redirect()->route('schedule.edit')->with('success', 'Horários de atendimento atualizados com sucesso!');
    }
}