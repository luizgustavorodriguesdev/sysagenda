<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class BusinessRegisteredUserController extends Controller
{
    /**
     * Mostra a view do formulário de registo para donos de negócio.
     */
    public function create(): View
    {
        return view('auth.register-business');
    }

    /**
     * Processa o pedido de registo de um dono de negócio.
     */
    public function store(Request $request): RedirectResponse
    {
        //dd('Estou no Controller CORRETO (BusinessRegisteredUserController)'); // ADICIONE ESTA LINHA
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_ADMIN, // Define a role como 'admin'
            'trial_ends_at' => now()->addDays(15), // Define o fim do trial para 15 dias no futuro
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard')); // Redireciona para o dashboard principal
    }
}