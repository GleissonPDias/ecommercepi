<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Platform;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Mostra uma lista de todos os produtos.
     */
    public function index()
    {
        $products = Product::with('game', 'platform')->latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Mostra o formulário para criar um novo produto.
     */
    public function create()
    {
        // Precisamos carregar os Jogos e Plataformas para os <select>
        $games = Game::orderBy('title')->get();
        $platforms = Platform::orderBy('name')->get();

        if ($games->isEmpty() || $platforms->isEmpty()) {
            // Se o banco estiver vazio, avisa o usuário (use o pré-requisito acima!)
            return redirect()->route('products.index')
                ->with('error', 'Você precisa ter pelo menos um Jogo e uma Plataforma cadastrados para criar um Produto.');
        }

        return view('products.create', compact('games', 'platforms'));
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
            'is_featured_main' => 'sometimes|boolean',
            'is_featured_secondary' => 'sometimes|boolean',
        ]);

        // 2. Lidar com Checkboxes (Campos Booleanos)
        // Um checkbox não marcado não envia valor, então precisamos definir o padrão
        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['is_featured_main'] = $request->has('is_featured_main');
        $validatedData['is_featured_secondary'] = $request->has('is_featured_secondary');

        // 3. Criar o Produto
        $product = Product::create($validatedData);

        // 4. Redirecionar para o próximo passo!
        // Redireciona o usuário direto para o formulário de requisitos do produto recém-criado.
        return redirect()->route('products.requirements.edit', $product)
            ->with('success', 'Produto criado com sucesso! Agora, adicione os requisitos.');
    }
}
