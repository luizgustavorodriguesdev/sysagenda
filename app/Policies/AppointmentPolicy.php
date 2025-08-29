<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    /**
     * Determina se o utilizador pode apagar (cancelar) um agendamento.
     * Este é o método que você perguntou.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // A regra de segurança:
        // O utilizador logado ($user) pode apagar este agendamento ($appointment)
        // APENAS SE o ID do negócio do utilizador for o mesmo que o ID do negócio
        // ao qual o serviço deste agendamento pertence.
        // A navegação é: Agendamento -> Serviço -> Negócio
        return $user->businesses()->first()->id === $appointment->service->business_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return false;
    }
}
