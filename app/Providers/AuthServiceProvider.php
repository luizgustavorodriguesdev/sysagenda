<?php

namespace App\Providers;

// Declarações 'use' necessárias
use App\Models\Barber;
use App\Policies\BarberPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // A nossa regra de autorização:
        // O modelo Barber é protegido pela classe BarberPolicy.
        Barber::class => BarberPolicy::class,
        Service::class => ServicePolicy::class,
        Appointment::class => AppointmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}