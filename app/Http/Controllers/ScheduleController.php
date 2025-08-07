<?php
// Em app/Http/Controllers/ScheduleController.php

namespace App\Http\Controllers;

use App\Models\Barber; // Importamos o modelo Barber
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Mostra o formulário para editar os horários de um barbeiro específico.
     */
    public function edit(Request $request)
    {
        // 1. Pega o negócio do utilizador logado.
        $business = $request->user()->businesses()->first();

        // Se não houver negócio, não há o que fazer.
        if (!$business) {
            return redirect()->route('dashboard')->with('error', 'Nenhum negócio encontrado.');
        }

        // 2. Busca todos os barbeiros deste negócio para preencher o dropdown.
        $barbers = $business->barbers()->get();

        // Se não houver barbeiros, não podemos definir horários.
        if ($barbers->isEmpty()) {
            return redirect()->route('barbers.index')->with('error', 'Precisa de adicionar um barbeiro antes de definir horários.');
        }

        // 3. Determina qual barbeiro está a ser editado.
        //    Se a URL tiver um '?barber=ID', usa esse ID.
        //    Caso contrário, pega o primeiro barbeiro da lista como padrão.
        $selectedBarberId = $request->query('barber', $barbers->first()->id);
        $selectedBarber = Barber::find($selectedBarberId);

        // 4. Busca os horários salvos para o barbeiro SELECIONADO.
        $schedules = $selectedBarber->schedules()->get();
        $scheduleByDay = [];
        foreach ($schedules as $schedule) {
            $scheduleByDay[$schedule->day_of_week] = [
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ];
        }

        // 5. Retorna a view, passando todos os dados necessários.
        $daysOfWeek = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
        return view('schedule.edit', compact('barbers', 'selectedBarber', 'scheduleByDay', 'daysOfWeek'));
    }

    /**
     * Atualiza os horários de atendimento para um barbeiro específico.
     */
    public function update(Request $request)
    {
        // 1. Validação para garantir que o ID do barbeiro foi enviado.
        $validated = $request->validate([
            'barber_id' => 'required|exists:barbers,id'
        ]);

        // 2. Encontra o barbeiro que estamos a editar.
        $barber = Barber::find($validated['barber_id']);
        $schedulesData = $request->input('schedules', []);

        // 3. A lógica de salvar/apagar horários continua a mesma,
        //    mas agora está ligada ao barbeiro em vez do negócio.
        foreach (range(0, 6) as $dayIndex) {
            if (isset($schedulesData[$dayIndex]['enabled'])) {
                // Valida e salva o horário para o barbeiro
                $request->validate([
                    "schedules.{$dayIndex}.start_time" => 'required',
                    "schedules.{$dayIndex}.end_time" => 'required|after:schedules.'.$dayIndex.'.start_time',
                ]);

                $barber->schedules()->updateOrCreate(
                    ['day_of_week' => $dayIndex],
                    [
                        'start_time' => $schedulesData[$dayIndex]['start_time'],
                        'end_time' => $schedulesData[$dayIndex]['end_time'],
                    ]
                );
            } else {
                // Apaga o horário para o barbeiro
                $barber->schedules()->where('day_of_week', $dayIndex)->delete();
            }
        }

        // Redireciona de volta para a mesma página, mantendo o barbeiro selecionado na URL,
        // e mostra uma mensagem de sucesso.
        return redirect()->route('schedule.edit', ['barber' => $barber->id])->with('success', 'Horários atualizados com sucesso!');
    }
}