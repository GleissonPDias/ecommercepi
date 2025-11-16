<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Category;
use Illuminate\Support\Facades\Auth; 

class SearchController extends Controller
{
    /**
     * Mostra os resultados da pesquisa (agora com filtros).
     */
    public function index(Request $request)
    {
        // 1. Pega o termo de busca da URL
        $query = $request->input('query');

        // 2. Se a busca estiver vazia (null ou string vazia), redireciona para a home
        if (empty($query)) {
            return redirect()->route('home');
        }

        // 3. Pega os inputs dos filtros
        $categoryId = $request->input('category_id');
        $sortBy = $request->input('sort_by', 'default');
        $maxPrice = $request->input('max_price');

        // 4. Começa a construir a busca (Query Builder)
        $productsQuery = Product::with('game', 'platform')
            ->where('is_active', true);
            
        // --- 👇 INÍCIO DA CORREÇÃO 👇 ---

        // 5. SÓ aplica o filtro de NOME/TÍTULO se a query NÃO FOR "*"
        //    (Se a query for "*", ele ignora este bloco e mostra tudo)
        if ($query && $query !== '*') {
            $productsQuery->where(function($q) use ($query) {
                // A) Procura no nome do Produto
                $q->where('name', 'LIKE', "%{$query}%")
                  // B) OU procura no título do Jogo
                  ->orWhereHas('game', function($gameQuery) use ($query) {
                      $gameQuery->where('title', 'LIKE', "%{$query}%");
                  });
            });
        }
        
        // --- 👆 FIM DA CORREÇÃO 👆 ---

        // 6. APLICA OS OUTROS FILTROS
        
        // --- Filtro de Categoria ---
        $productsQuery->when($categoryId, function ($q) use ($categoryId) {
            return $q->whereHas('game.categories', function($catQuery) use ($categoryId) {
                $catQuery->where('category_id', $categoryId);
            });
        });

        // --- Filtro de Preço Máximo ---
        $productsQuery->when($maxPrice, function ($q) use ($maxPrice) {
            return $q->where('current_price', '<=', $maxPrice);
        });

        // --- Filtro de Ordem (Sort By) ---
        match ($sortBy) {
            'price_asc' => $productsQuery->orderBy('current_price', 'asc'),
            'price_desc' => $productsQuery->orderBy('current_price', 'desc'),
            'name_asc' => $productsQuery->orderBy('name', 'asc'),
            'name_desc' => $productsQuery->orderBy('name', 'desc'),
            default => $productsQuery->latest(), // Padrão: Mais recentes
        };

        // 7. Executa a busca final com paginação
        $products = $productsQuery->paginate(12)->appends($request->query());
        
        // 8. Busca todas as categorias (para o dropdown do filtro)
        $categories = Category::orderBy('name')->get();

        // 9. Retorna a view de resultados
        return view('search.results', [
            'products' => $products,
            'categories' => $categories, 
            'query' => $query, // Passa o $query para a view (para o <h2> e o <input hidden>)
        ]);
    }
}