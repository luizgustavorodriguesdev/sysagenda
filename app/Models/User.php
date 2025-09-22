<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Billable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_CLIENT = 'client';
    public const ROLE_SUPER_ADMIN = 'super-admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plan_id', 
        'subscription_ends_at',
        'role',   
        'trial_ends_at'     
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isSubscriptionActive(): bool
    {
        return $this->subscription_ends_at && $this->subscription_ends_at->isFuture();
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    // Relação com pagamentos
    // Em User.php
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->latest(); // .latest() ordena os pagamentos do mais recente para o mais antigo
    }

    /**
     * NOVO MÉTODO: O Sincronizador de Assinatura
     *
     * Calcula a data de fim da assinatura com base no histórico de pagamentos ativos
     * e atualiza o registo do utilizador.
     */
    public function updateSubscriptionStatusFromPayments(): void
    {
        // 1. Busca o pagamento ATIVO deste utilizador que tem a data de validade mais recente.
        $latestActivePayment = $this->payments()
                                    ->where('status', 'active')
                                    ->orderBy('new_subscription_ends_at', 'desc')
                                    ->first();

        // 2. Determina qual deve ser a nova data de fim da assinatura.
        $newSubscriptionEndDate = $latestActivePayment
                                    ? $latestActivePayment->new_subscription_ends_at // Se encontrou um, usa a data dele.
                                    : null; // Se não encontrou nenhum, a assinatura está inativa (null).

        // 3. Atualiza o utilizador com a nova data correta.
        $this->update([
            'subscription_ends_at' => $newSubscriptionEndDate,
            // Também atualizamos o plano para o do último pagamento ativo, se houver.
            'plan_id' => $latestActivePayment ? $latestActivePayment->plan_id : $this->plan_id,
        ]);
    }
}