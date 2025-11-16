<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // Precisamos do 'Product'
use App\Models\GameKey; // Precisamos do 'GameKey'
use Illuminate\Http\Request;

class GameKeyController extends Controller
{
    /**
     * Mostra a página de gestão de chaves para um produto.
     * (O $product vem da rota: /products/{product}/keys)
     */
    public function index(Product $product)
    {
        // Carrega todas as chaves associadas a este produto
        // 'latest()' mostra as mais recentes primeiro
        $keys = $product->keys()->latest()->paginate(20); 

        return view('admin.game_keys.index', [
            'product' => $product,
            'keys' => $keys
        ]);
    }

    /**
     * Salva as novas chaves (coladas no textarea) no banco.
     */
    public function store(Request $request, Product $product)
    {
        // 1. Validação: o campo 'keys_list' não pode estar vazio
        $request->validate([
            'keys_list' => 'required|string',
        ]);

        // 2. Pega o texto do textarea e quebra por linha
        //    Isto transforma a lista colada num array
        $keysArray = preg_split("/\r\n|\n|\r/", $request->keys_list);

        $count = 0;
        foreach ($keysArray as $keyString) {
            // Remove espaços em branco antes/depois da chave
            $trimmedKey = trim($keyString); 

            // 3. Ignora linhas vazias
            if (empty($trimmedKey)) {
                continue;
            }

            // 4. Cria a chave no banco de dados, já associada ao produto
            $product->keys()->create([
                'key_value' => $trimmedKey,

            ]);

            $count++;
        }

        return back()->with('success', "$count chaves foram adicionadas com sucesso!");
    }

    /**
     * Apaga uma chave específica.
     */
    public function destroy(GameKey $key)
    {
        // 🛡️ Segurança: Não deixa apagar uma chave que já foi vendida
        if ($key->is_sold) {
            return back()->with('error', 'Não é possível apagar uma chave que já foi vendida.');
        }

        $key->delete();

        return back()->with('success', 'Chave apagada com sucesso.');
    }
}