<?php

// 1. CORREÇÃO: O namespace DEVE incluir o '\Admin'
namespace App\Http\Controllers\Admin; 

// 2. IMPORTS: Importe o Controller base e os Models
use App\Http\Controllers\Controller; 
use App\Models\Game;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Http\Request;

// 3. O nome da classe está correto
class ProductController extends Controller
{
    /**
     * Mostra o formulário de criação de produto.
     */
    public function create()
    {
        // 1. Busca os jogos e plataformas
        $games = Game::orderBy('title')->get();
        $platforms = Platform::orderBy('name')->get();


        return view('admin.products.create', [ 
            'games' => $games,
            'platforms' => $platforms,
        ]);
    }

    /**
     * Salva o novo produto no banco de dados.
     */
    public function store(Request $request)
    {
        // 1. Validação
        $validatedData = $request->validate([
            'game_id' => 'required|exists:games,id',
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:255',
            'default_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'is_active' => 'sometimes|boolean',
            'is_featured_secondary' => 'sometimes|boolean',
        ]);

        // 2. Lidar com Checkboxes
        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['is_featured_secondary'] = $request->has('is_featured_secondary');

        // 3. Criar o Produto
        $product = Product::create($validatedData);

        // 4. Redirecionar
        return redirect()->route('admin.products.requirements.edit', $product)
            ->with('success', 'Produto criado com sucesso! Agora, adicione os requisitos.');
    }
}