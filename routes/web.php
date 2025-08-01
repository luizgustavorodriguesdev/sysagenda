<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota da página inicial.
Route::get('/', function () {
    return view('welcome');
});

//======================================================================
// ROTAS PÚBLICAS (APIs e rotas específicas)
//======================================================================

// Rota para a nossa API interna de disponibilidade que retorna JSON.
Route::get('/api/availability/service/{service}/date/{date}', [PublicBookingController::class, 'getAvailability'])->name('public.booking.availability');

// Rota para salvar o agendamento final feito pelo cliente.
Route::post('/booking/store', [PublicBookingController::class, 'storeBooking'])->name('public.booking.store');


//======================================================================
// ROTAS PRIVADAS (Exigem que o usuário esteja autenticado)
//======================================================================

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('business', BusinessController::class)->only(['create', 'store']);
    Route::resource('service', ServiceController::class);

    Route::get('/schedule/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
    Route::put('/schedule/update', [ScheduleController::class, 'update'])->name('schedule.update');

    // Rota para listar os agendamentos do negócio
    Route::get('/appointments', [App\Http\Controllers\AppointmentController::class, 'index'])->name('appointments.index');
});


//======================================================================
// ORDEM FINAL DAS ROTAS
//======================================================================

// 1. Carrega todas as rotas de autenticação do Breeze (/login, /register, etc.).
require __DIR__.'/auth.php';

// 2. POR ÚLTIMO, a rota "apanha-tudo" do slug do negócio.
//    Agora, se uma rota como /login for encontrada acima, o Laravel usá-la-á primeiro.
Route::get('/{business:slug}', [PublicBookingController::class, 'show'])->name('public.booking.show');