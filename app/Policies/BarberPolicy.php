<?php
// Em app/Policies/BarberPolicy.php

namespace App\Policies;

use App\Models\Barber;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarberPolicy
{
    /**
     * Determina se o utilizador pode criar barbeiros.
     */
    public function create(User $user): bool
    {
        // Um utilizador pode criar um barbeiro se ele for um admin (dono de negócio).
        return $user->isAdmin();
    }

    /**
     * Determina se o utilizador pode atualizar um barbeiro.
     */
    public function update(User $user, Barber $barber): bool
    {
        // O utilizador pode atualizar este barbeiro SE o business_id do barbeiro
        // for igual ao ID do primeiro negócio deste utilizador.
        return $user->businesses()->first()->id === $barber->business_id;
    }

    /**
     * Determina se o utilizador pode apagar um barbeiro.
     */
    public function delete(User $user, Barber $barber): bool
    {
        // A lógica é a mesma da atualização.
        return $user->businesses()->first()->id === $barber->business_id;
    }

    // ... (outros métodos que podemos usar no futuro)
}