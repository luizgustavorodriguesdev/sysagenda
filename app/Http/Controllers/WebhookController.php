<?php

namespace App\Http\Controllers;

use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class WebhookController extends CashierController
{
    // O Laravel Cashier já trata de toda a lógica de verificação
    // e de chamar os eventos corretos (como o WebhookReceived que estamos a usar).
    // Não precisamos de adicionar mais nada aqui por agora.
}