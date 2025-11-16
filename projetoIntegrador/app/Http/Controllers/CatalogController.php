<?php

namespace App\Http\Controllers;

use App\Models\Platform; // 👈 Importa o Model da Plataforma
use App\Models\Product;  // 👈 Importa o Model do Produto
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function showByPlatform(Platform $platform)
    {
        // 1. Busca os produtos que pertencem a esta plataforma
        $products = Product::with('game', 'platform') // Carrega os 'games' e 'platform'
            ->where('platform_id', $platform->id)
            ->where('is_active', true) // Mostra apenas os produtos ativos
            ->latest()
            ->paginate(12); // Pagina os resultados (12 por página)

        // 2. Retorna a view (que vamos criar a seguir),
        //    passando os produtos encontrados e a plataforma clicada.
        return view('catalog.index', [
            'products' => $products,
            'platform' => $platform
        ]);
    }
}
