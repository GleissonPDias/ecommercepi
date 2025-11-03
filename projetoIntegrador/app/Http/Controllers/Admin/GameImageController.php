<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Importe o Storage

class GameImageController extends Controller
{
    /**
     * Mostra o gerenciador de imagens para um jogo.
     */
    public function index(Game $game)
    {
        // Carrega as imagens que já existem
        $game->load('images'); 
        
        return view('admin.games.images', compact('game'));
    }

    /**
     * Salva uma nova imagem para o jogo.
     */
    public function store(Request $request, Game $game)
    {
        // 1. Valida o arquivo
        $request->validate([
            // 'images' é o nome do <input type="file" multiple>
            'images' => 'required|array', // Espera um array de imagens
            'images.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // Regras para cada imagem
        ]);

        // 2. Faz o loop e salva cada imagem
        foreach ($request->file('images') as $image) {
            
            // 3. Salva o arquivo na pasta 'storage/app/public/game_images'
            // A variável $path conterá algo como "game_images/nome_arquivo_aleatorio.jpg"
            $path = $image->store('game_images', 'public');

            // 4. Salva o caminho no banco de dados, associado ao jogo
            $game->images()->create([
                'image_url' => $path
            ]);
        }

        return redirect()->back()->with('success', 'Imagens salvas!');
    }

    /**
     * Deleta uma imagem.
     */
    public function destroy(GameImage $image)
    {
        // 1. Deleta o arquivo do disco (storage/app/public/...)
        Storage::disk('public')->delete($image->image_url);
        
        // 2. Deleta o registro do banco de dados
        $image->delete();

        return redirect()->back()->with('success', 'Imagem deletada!');
    }
}