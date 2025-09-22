<?php
// Em app/Http/Controllers/SuperAdmin/PaymentController.php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Guarda um novo pagamento e sincroniza o status do cliente.
     */
    // Em app/Http/Controllers/SuperAdmin/PaymentController.php

    public function store(Request $request, $userId) // Recebemos o ID em vez do objeto User
    {
        // Primeiro, encontramos o utilizador na base de dados usando o ID.
        // O findOrFail() irá falhar com um erro 404 se o utilizador não for encontrado.
        $user = \App\Models\User::findOrFail($userId);

        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string',
            'payment_date' => 'required|date',
            'new_subscription_ends_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'active';

        // Agora que temos a certeza de que a variável $user contém o utilizador correto,
        // a relação irá funcionar como esperado.
        $user->payments()->create($validated);

        $user->updateSubscriptionStatusFromPayments();

        return redirect()->back()->with('success', 'Pagamento registado e assinatura atualizada!');
    }

    /**
     * Mostra o formulário para editar um pagamento.
     */
    public function edit(Payment $payment)
    {
        return view('superadmin.payments.edit', compact('payment'));
    }

    /**
     * Atualiza um pagamento e sincroniza o status do cliente.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([/*...*/]); // Sua validação existente

        // 1. Atualiza o registo do pagamento.
        $payment->update($validated);

        // 2. CHAMA O NOSSO SINCRONIZADOR
        // Pega o utilizador associado a este pagamento e atualiza o seu status.
        $payment->user->updateSubscriptionStatusFromPayments();

        return redirect()->route('superadmin.clients.edit', $payment->user_id)->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Apaga um pagamento e sincroniza o status do cliente.
     */
    public function destroy(Payment $payment)
    {
        // Guarda o utilizador antes de apagar o pagamento, para sabermos quem atualizar.
        $user = $payment->user;

        // 1. Apaga o registo do pagamento.
        $payment->delete();

        // 2. CHAMA O NOSSO SINCRONIZADOR
        // Pede ao utilizador para recalcular o seu status com base nos pagamentos restantes.
        $user->updateSubscriptionStatusFromPayments();

        return redirect()->route('superadmin.clients.edit', $user)->with('success', 'Registo de pagamento apagado e assinatura recalculada!');
    }
}