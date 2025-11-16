<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// --- 👇 ADICIONE ESTAS 3 LINHAS 'USE' 👇 ---
use Illuminate\Support\Facades\View; // 1. Importa o 'View' (corrige o seu erro)
use Illuminate\Support\Facades\Auth; // 2. Importa o 'Auth' (necessário para Auth::check())
use App\Models\CartItem; // 3. Importa o 'CartItem' (necessário para CartItem::where())

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // =========================================================
        // A LÓGICA DO VIEW COMPOSER
        // =========================================================
        
        // Isto partilha dados com o 'layouts.app' (o seu molde mestre)
        View::composer('layouts.app', function ($view) {
            
            $cartItems = collect(); // 1. Começa com um carrinho vazio (para visitantes)

            if (Auth::check()) {
                // 2. Se o utilizador estiver logado, busca os itens
                $cartItems = CartItem::where('user_id', Auth::id())->get();
            }

            // 3. Envia a variável $cartItems para a view 'layouts.app'
            $view->with('cartItems', $cartItems);
        });
        
        // =========================================================
    }
}