<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            
            $key = Str::lower($credentials['email']).'|'.$request->ip();

            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                throw ValidationException::withMessages([
                    'email' => "Muitas tentativas de login. Tente novamente em {$seconds} segundos.",
                ]);
            }

            $remember = $request->boolean('remember');
            $password = $credentials['password'];

            if (! Auth::attempt(['email' => $credentials['email'], 'password' => $password], $remember)) {
                RateLimiter::hit($key);

                throw ValidationException::withMessages([
                    'email' => 'As credenciais fornecidas não conferem com nossos registros.',
                ]);
            }

            RateLimiter::clear($key);

            $request->session()->regenerate();

            $user = $request->user();

            if (! $user->is_admin) {
                $intended = $request->session()->get('url.intended');

                if ($intended && Str::startsWith($intended, url('/admin'))) {
                    $request->session()->forget('url.intended');
                }
            }

            if ($user->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended('/')->with('sucesso', 'Login realizado com sucesso!');
            
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'email' => 'Ocorreu um erro ao processar seu login. Por favor, tente novamente.',
            ]);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telefone' => $validated['telefone'] ?? null,
                'aceitou_termos_em' => now(),
                'password' => Hash::make($validated['password']),
            ]);

            return redirect('/login')->with('sucesso', 'Cadastro realizado com sucesso!');
            
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('erro', 'Ocorreu um erro ao criar sua conta. Por favor, tente novamente.');
        }
    }

    /**
     * Método de logout que zera a sessão e redireciona.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('sucesso', 'Você saiu da sua conta.');
    }
}
