<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

// Grupo de rotas protegidas: só acessível para usuários autenticados
//O uso do Route::middleware('auth') é uma prática comum no Laravel para proteger áreas do sistema que exigem login, como perfil, dashboard e cadastro de negócios.
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('business', App\Http\Controllers\BusinessController::class)
        ->only(['create', 'store']); 
});

require __DIR__.'/auth.php';
