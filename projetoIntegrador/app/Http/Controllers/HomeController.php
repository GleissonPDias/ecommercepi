<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\CarouselSlide; // 1. Não se esqueça de importar o CarouselSlide

class HomeController extends Controller
{
    public function index()
    {
        // --- 1. Buscando o Carrossel Principal ---
        // (Esta parte está correta e usa a nova lógica)
        $carouselSlides = CarouselSlide::with('products.game', 'products.platform')
                                    ->where('is_active', true)
                                    ->orderBy('order')
                                    ->get();

        // --- 2. Buscando a Vitrine Padrão ("Mais Populares") ---
        // (ESTA É A CORREÇÃO)
        $products = Product::with('game', 'platform')
                           ->where('is_active', true)
                           // ->whereNull('featured_slot') // LINHA REMOVIDA
                           ->latest()
                           ->get();

        $cartItems = collect();

        if (Auth::check()){
            $cartItems = Auth::user()->cartItems()->with('product.game')->get();
        }

        
        // --- 3. Enviando TUDO para a View ---
        return view('home', [
            'carouselSlides' => $carouselSlides,
            'products' => $products,
            'cartItems' => $cartItems,
        ]);
    }
    
public function show(Product $product)
{
    // 1. Carrega TUDO o que precisamos de uma vez.
    // Adicionei 'game.baseGame.product' para já carregar o produto-pai se ele existir.
    $product->load(
        'game.developer',
        'game.publisher',
        'game.categories',
        'platform',
        'systemRequirements',
        'game.images',
        'game.baseGame.product' // <-- Otimização: já carrega o produto do jogo-base
    );
    $cartItems = collect();

    if (Auth::check()){
        $cartItems = Auth::user()->cartItems()->with('product.game')->get();
    }

    // 2. Prepara as variáveis
    $game = $product->game;
    $dlcProducts = collect();
    $baseGameProduct = null;

    // 3. Lógica Principal (COM A ESTRUTURA CORRIGIDA)
    if ($game) { // Primeiro, checa se este produto tem um jogo

        // CASO A: Se for um Jogo-Base (não tem pai)
        if ($game->base_game_id === null) {
            
            // Busca os "jogos-filhos" (DLCs) e seus produtos
            $dlcGames = $game->dlcs()->with('product.game')->get(); 
            
            $dlcProducts = $dlcGames->map(function ($dlcGame) {
                return $dlcGame->product;
            })->filter();

        } 
        // CASO B: Se for uma DLC (tem um pai)
        else { 
            
            // Graças ao 'load()' lá de cima, já podemos checar
            if ($game->baseGame && $game->baseGame->product) {
                $baseGameProduct = $game->baseGame->product;
            }
        }
    } // Fim do if ($game)

    // 4. Retorna a View
    return view('products.show', [
        'product' => $product,
        'dlcProducts' => $dlcProducts,
        'baseGameProduct' => $baseGameProduct,
        'cartItems' => $cartItems,
    ]);
}
}