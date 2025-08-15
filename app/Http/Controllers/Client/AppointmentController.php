<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * "Apaga" um agendamento do cliente, alterando o seu status para 'cancelled'.
     */
    public function destroy(Appointment $appointment)
    {
        // ================== VERIFICAÇÃO DE SEGURANÇA CRUCIAL ==================
        // Verificamos se o 'user_id' do agendamento que se está a tentar cancelar
        // é o mesmo ID do utilizador que está atualmente logado.
        // Isto impede que um utilizador cancele agendamentos de outros utilizadores.
        if ($appointment->user_id !== auth()->id()) {
            // Se os IDs não corresponderem, abortamos a operação com um erro "403 Forbidden".
            abort(403, 'Acesso Não Autorizado.');
        }
        // =====================================================================


        // Se a verificação passar, a lógica é a mesma do painel de admin.
        $appointment->status = 'cancelled';
        $appointment->save();

        // Redireciona de volta para o painel do cliente com uma mensagem de sucesso.
        return redirect()->route('client.dashboard')->with('success', 'Agendamento cancelado com sucesso!');
    }
}