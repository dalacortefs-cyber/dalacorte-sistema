<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Models\LogAtividade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthPainelController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'O e-mail é obrigatório.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ]);

        $key = 'login.' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Muitas tentativas. Tente novamente em {$seconds} segundos.",
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages([
                'email' => 'E-mail ou senha incorretos.',
            ]);
        }

        $user = Auth::user();

        if (!$user->active) {
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Usuário inativo. Entre em contato com o administrador.',
            ]);
        }

        RateLimiter::clear($key);
        $request->session()->regenerate();

        LogAtividade::registrar('Auth', 'LOGIN', "Login realizado por {$user->name}");

        return redirect()->intended('/painel');
    }

    public function logout(Request $request)
    {
        $nome = Auth::user()?->name ?? '';
        LogAtividade::registrar('Auth', 'LOGOUT', "Logout de {$nome}");

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Você saiu com segurança.');
    }
}
