<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BarberController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

//======================================================================
// ROTAS PÚBLICAS
//======================================================================
Route::get('/api/availability/service/{service}/date/{date}', [PublicBookingController::class, 'getAvailability'])->name('public.booking.availability');
Route::post('/booking/store', [PublicBookingController::class, 'storeBooking'])->name('public.booking.store');


//======================================================================
// ROTAS PRIVADAS (Exigem Autenticação)
//======================================================================

Route::middleware('auth')->group(function () {

    // Rota principal do Dashboard (com lógica de redirecionamento)
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return view('dashboard');
        }
        // ESTA LINHA CAUSA O ERRO SE A ROTA ABAIXO NÃO EXISTIR
        return redirect()->route('client.dashboard');
    })->middleware(['verified'])->name('dashboard');

    // --- Rotas do Dono do Negócio (Admin) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('business', BusinessController::class)->only(['create', 'store']);
    Route::resource('service', ServiceController::class);
    Route::get('/schedule/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
    Route::put('/schedule/update', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::resource('barbers', BarberController::class);

    // --- ROTAS DO CLIENTE ---
    // ESTE BLOCO PRECISA DE EXISTIR PARA O ERRO DESAPARECER
    Route::prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');
    });
});


//======================================================================
// ROTAS DE AUTENTICAÇÃO E PÚBLICA PRINCIPAL
//======================================================================
require __DIR__.'/auth.php';

Route::get('/{business:slug}', [PublicBookingController::class, 'show'])->name('public.booking.show');