<?php

namespace App\Listeners;

use App\Models\Plan;
use App\Models\User;
use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Log; // IMPORTANTE: Adicione esta linha

class StripeEventListener
{
    public function handle(WebhookReceived $event): void
    {
        // Escreve no log que recebemos um evento do tipo que nos interessa.
        if ($event->payload['type'] === 'checkout.session.completed') {
            Log::info('Webhook checkout.session.completed recebido!');

            $session = $event->payload['data']['object'];
            $stripeCustomerId = $session['customer'];
            Log::info('ID do Cliente no Stripe: ' . $stripeCustomerId);

            // Usamos o ID do cliente do Stripe para encontrar o nosso utilizador local.
            $user = User::where('stripe_id', $stripeCustomerId)->first();

            // Verificamos se encontrámos um utilizador.
            if ($user) {
                Log::info('Utilizador encontrado na base de dados: ID ' . $user->id);

                if ($user->plan_id) {
                    Log::info('O utilizador já tem um plan_id. Nenhuma ação necessária.');
                    return; // Sai da função se o utilizador já tiver um plano.
                }

                $stripePlanId = null;
                // Estrutura da API do Stripe para assinaturas
                if (isset($session['subscription_details']['items']['data'][0]['price']['id'])) {
                    $stripePlanId = $session['subscription_details']['items']['data'][0]['price']['id'];
                }

                Log::info('ID do Plano no Stripe: ' . $stripePlanId);

                if ($stripePlanId) {
                    // Encontramos o nosso plano local que corresponde ao ID do Stripe.
                    $localPlan = Plan::where('stripe_plan_id', $stripePlanId)->first();

                    if ($localPlan) {
                        Log::info('Plano local correspondente encontrado: ID ' . $localPlan->id);
                        // ATUALIZAMOS O NOSSO CAMPO PERSONALIZADO!
                        $user->plan_id = $localPlan->id;
                        $user->save();
                        Log::info('SUCESSO! O plan_id do utilizador foi atualizado para: ' . $localPlan->id);
                    } else {
                        Log::error('ERRO: Nenhum plano local encontrado com o stripe_plan_id: ' . $stripePlanId);
                    }
                } else {
                     Log::warning('AVISO: Não foi possível extrair o stripe_plan_id do payload do webhook.');
                }
            } else {
                Log::error('ERRO CRÍTICO: Nenhum utilizador encontrado com o stripe_id: ' . $stripeCustomerId);
            }
        }
    }
}