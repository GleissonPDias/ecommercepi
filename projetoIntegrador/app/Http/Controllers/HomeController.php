<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
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


        // --- 3. Enviando TUDO para a View ---
        return view('home', [
            'carouselSlides' => $carouselSlides,
            'products' => $products,
        ]);
    }
    
    public function show(Product $product)
    {
        // (Seu método show() está perfeito, mantenha-o)
        $product->load('game.developer', 'platform', 'systemRequirements', 'game.images');
        return view('products.show', [
            'product' => $product
        ]);
    }
}