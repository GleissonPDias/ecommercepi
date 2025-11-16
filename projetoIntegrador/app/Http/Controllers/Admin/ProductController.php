<?php

// 1. CORREÇÃO: O namespace DEVE incluir o '\Admin'
namespace App\Http\Controllers\Admin; 

// 2. IMPORTS: Importe o Controller base e os Models
use App\Http\Controllers\Controller; 
use App\Models\Game;
use App\Models\Platform;
use App\Models\Product;
use App\Models\game_modes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        // 2. Busque todos os produtos com seus relacionamentos
        $products = Product::with('game', 'platform')
                            ->latest()
                            ->paginate(15);



        // 3. Envie os produtos para a nova view
        return view('admin.products.index', [
            'products' => $products,
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

    public function edit(Product $product){
        

        $games = Game::orderBy('title')->get();
        $platforms = Platform::orderBy('name')->get();



        return view('admin.products.edit', [
            'product' => $product,
            'games' => $games,
            'platforms' => $platforms,
        ]);
    }

public function update(Request $request, Product $product)
    {
        // 1. Validação (Corrigida para ser igual à do 'store')
        $validatedData = $request->validate([
            'game_id' => 'required|exists:games,id', // Corrigido (sem espaço)
            'platform_id' => 'required|exists:platforms,id',
            'name' => 'required|string|max:255',
            'default_price' => 'required|numeric|min:0', // Corrigido (era 'decimal')
            'current_price' => 'required|numeric|min:0', // Corrigido (era 'decimal')
            'is_active' => 'sometimes|boolean', // Adicionado
            'is_featured_secondary' => 'sometimes|boolean', // Adicionado
        ]);

        // 2. Lidar com Checkboxes (O seu código 'store' já tinha isto, estava correto)
        $validatedData['is_active'] = $request->has('is_active');
        $validatedData['is_featured_secondary'] = $request->has('is_featured_secondary');

        // 3. Atualizar o Produto (A linha que faltava)
        // O $product já vem da rota (Route-Model Binding)
        $product->update($validatedData);

        // 4. Redirecionar de volta para a lista (A linha que faltava)
        return redirect()->route('admin.products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }


    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {

            $product->delete();
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.products.index')->with('error', 'Erro ao deletar o produto.');
        }

        return redirect()->route('admin.products.index')->with('success', 'Produto deletado com sucesso.');
    }

    

}