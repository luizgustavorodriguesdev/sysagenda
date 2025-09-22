<?php

// Todas as nossas classes de Controller e Middleware
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\Client\AppointmentController as ClientAppointmentController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\BusinessRegisteredUserController;
use App\Http\Controllers\ClientManagementController;
use App\Http\Middleware\CheckSubscription;
use App\Http\Middleware\IsSuperAdmin;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\PaymentController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\PlanController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROTAS PÚBLICAS GERAIS ---
Route::get('/', function () {
    return view('welcome');
});
Route::get('/api/availability/service/{service}/date/{date}', [PublicBookingController::class, 'getAvailability'])->name('public.booking.availability');
Route::post('/booking/store', [PublicBookingController::class, 'storeBooking'])->name('public.booking.store');

// --- GRUPO DE ROTAS QUE EXIGEM AUTENTICAÇÃO ---
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        if (auth()->user()->isSuperAdmin()) { return redirect()->route('superadmin.dashboard'); }
        if (auth()->user()->isAdmin()) { return view('dashboard'); }
        return redirect()->route('client.dashboard');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/subscription/expired', function() { return view('subscription.expired'); })->name('subscription.expired');
    
    // --- ROTAS DO DONO DO NEGÓCIO (Protegidas pela Verificação de Assinatura) ---
    // Todas as rotas aqui dentro passarão primeiro pelo nosso "segurança" CheckSubscription.
    Route::middleware(CheckSubscription::class)->group(function() {
        Route::resource('business', BusinessController::class)->only(['create', 'store']);
        Route::resource('service', ServiceController::class);
        Route::get('/schedule/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
        Route::put('/schedule/update', [ScheduleController::class, 'update'])->name('schedule.update');
        Route::resource('appointments', AppointmentController::class)->only(['index', 'destroy']);
        Route::resource('barbers', BarberController::class);
        Route::get('/clients', [ClientManagementController::class, 'index'])->name('clients.index');
    });

    // --- Rotas do Cliente ---
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
        Route::delete('/appointments/{appointment}', [ClientAppointmentController::class, 'destroy'])->name('appointments.destroy');
    });

    // --- Rotas de Assinatura (para escolher o plano) ---
    Route::get('/subscribe', [SubscriptionController::class, 'index'])->name('subscribe.index');
    Route::post('/subscribe', [SubscriptionController::class, 'store'])->name('subscribe.store');

    // Rota de Perfil (comum a todos, não está no grupo CheckSubscription)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', IsSuperAdmin::class])
    ->prefix('admincp') // Todas as URLs começarão com /admincp/...
    ->name('superadmin.') // Todos os nomes de rota começarão com 'superadmin.'
    ->group(function () {

        // Dashboard Principal
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Gestão de Clientes (Donos de Negócio)
        Route::get('/clients/create', [DashboardController::class, 'createClient'])->name('clients.create');
        Route::post('/clients', [DashboardController::class, 'storeClient'])->name('clients.store');
        Route::get('/clients/{user}/edit', [DashboardController::class, 'editClient'])->name('clients.edit');
        Route::put('/clients/{user}', [DashboardController::class, 'updateClient'])->name('clients.update');

        // Gestão de Planos (CRUD Completo)
        Route::resource('plans', PlanController::class);

        // Gestão de Pagamentos
        Route::post('/clients/{user}/payments', [PaymentController::class, 'store'])->name('clients.payments.store');
        Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });

// Rota para mostrar o formulário de registo do Dono de Negócio
Route::get('/register/business', [BusinessRegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register.business');

// Rota para processar o formulário de registo do Dono de Negócio
Route::post('/register/business', [BusinessRegisteredUserController::class, 'store'])
                ->middleware('guest');

// --- ROTAS DE AUTENTICAÇÃO E PÚBLICA PRINCIPAL ---
require __DIR__.'/auth.php';

Route::get('/{business:slug}', [PublicBookingController::class, 'show'])->name('public.booking.show');