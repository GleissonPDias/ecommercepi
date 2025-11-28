<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    // 1. Redireciona o usuário para a página do Google
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // 2. Recebe o usuário de volta do Google
    public function handleProviderCallback($provider)
    {
        try {
            // Pega os dados do usuário no Google
            $socialUser = Socialite::driver($provider)->user();

            // Verifica se este usuário já existe no nosso banco (pelo email ou ID)
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Se existe, mas não tem o provider_id vinculado, atualizamos
                if (!$user->provider_id) {
                    $user->update([
                        'provider_id' => $socialUser->getId(),
                        'provider_name' => $provider,
                        'avatar' => $socialUser->getAvatar(),
                    ]);
                }
            } else {
                // Se não existe, criamos um novo usuário
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider_id' => $socialUser->getId(),
                    'provider_name' => $provider,
                    'avatar' => $socialUser->getAvatar(),
                    'password' => null, // Sem senha
                    'email_verified_at' => now(), // Google já verificou o email
                ]);
            }

            // Faz o Login manual do usuário
            Auth::login($user);

            // Redireciona para a Home ou Dashboard
            return redirect()->route('home');

        } catch (\Exception $e) {
            // Se der erro, volta para o login
            return redirect()->route('login')->with('error', 'Erro ao entrar com Google.');
        }
    }
}