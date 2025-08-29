<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // 1. A primeira verificação: este segurança só se aplica aos "donos de negócio" (role = admin).
        //    Se for um 'super-admin' ou um 'client', deixamo-lo passar sem verificação.
        if (!$user->isAdmin()) {
            return $next($request);
        }

        // 2. A verificação principal: o período de teste OU a assinatura paga estão ativos?
        //    Usamos os métodos que criámos no modelo User.
        if ($user->isOnTrial() || $user->isSubscriptionActive()) {
            // Se pelo menos um estiver ativo, permite que o pedido continue e aceda à página solicitada.
            return $next($request);
        }
        
        // 3. Verificação especial: o utilizador está a tentar aceder à sua própria página de perfil?
        //    É uma boa prática permitir sempre o acesso à página de perfil,
        //    mesmo que a assinatura tenha expirado.
        if ($request->routeIs('profile.edit')) {
            return $next($request);
        }

        // 4. Se nenhuma das condições for verdadeira, o acesso é bloqueado.
        //    Redirecionamos o utilizador para uma página a informar que a conta expirou.
        return redirect()->route('subscription.expired');
    }
}