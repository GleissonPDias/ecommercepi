<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;


class SearchController extends Controller
{
    /**
     * Mostra os resultados da pesquisa.
     */
    public function index(Request $request)
    {
        // 1. Pega o termo de busca da URL (o ?query=...)
        $query = $request->input('query');

        $cartItems = Auth::user()->cartItems()->with('product.game')->get();

        if($cartItems->isEmpty()){
            return redirect()->route('cart.index')->with('error', 'O seu carrinho está vazio!');
        }

        // 2. Se a busca estiver vazia, apenas redireciona para a home
        if (!$query) {
            return redirect()->route('home');
        }

        // 3. FAZ A BUSCA NO BANCO DE DADOS
        // Esta é uma busca poderosa que procura:
        // - No NOME do Produto (products.name)
        // - OU no TÍTULO do Jogo (games.title) relacionado
        $products = Product::with('game', 'platform')
            ->where('is_active', true)
            ->where(function($q) use ($query) {

                // A) Procura no nome do Produto
                $q->where('name', 'LIKE', "%{$query}%")

                  // B) Procura no título do Jogo
                  ->orWhereHas('game', function($gameQuery) use ($query) {
                      $gameQuery->where('title', 'LIKE', "%{$query}%");
                  });
            })
            ->paginate(12); // Pagina os resultados (12 por página)

        // 4. Retorna a view de resultados (que vamos criar a seguir)
        return view('search.results', [
            'products' => $products, // Os produtos encontrados
            'query' => $query,        // O termo que o usuário digitou
            'cartItems' => $cartItems
        ]);
    }
}