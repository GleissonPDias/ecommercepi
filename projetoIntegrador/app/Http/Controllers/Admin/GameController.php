<?php

namespace App\Http\Controllers\Admin;

// --- 1. Imports Necessários ---
use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class GameController extends Controller
{
    /**
     * Mostra a lista de todos os jogos cadastrados.
     * (Para a sua view admin.games.index)
     */
    public function index()
    {
        // Carrega os jogos com os relacionamentos e contagens
        // para evitar o problema de "N+1 query" na sua tabela.
        $games = Game::with('developer', 'publisher')
                    ->withCount('images', 'categories')
                    ->latest()
                    ->paginate(15); // Pagina a cada 15 resultados

        return view('admin.games.index', [
            'games' => $games
        ]);

    }

    /**
     * Mostra o formulário "tudo-em-um" para criar um novo jogo.
     */
    public function create()
    {
        // Busca os dados existentes para os dropdowns
        $developers = Developer::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $baseGames = Game::whereNull('base_game_id')->orderBy('title')->get();

        return view('admin.games.create', [
            'developers' => $developers,
            'publishers' => $publishers,
            'categories' => $categories,
            'baseGames' => $baseGames
        ]);

        
    }

    /**
     * Salva o novo Jogo, e se necessário, o Desenvolvedor, Editor,
     * Categorias e Imagens, tudo de uma vez.
     */
    public function store(Request $request)
    {
        // 1. Validar TUDO de uma vez
        $validated = $request->validate([
            // Dados do Jogo
            'title' => 'required|string|max:255',
            'about' => 'required|string',
            'cover_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // Upload da Capa
            'release_date' => 'required|date',
            'age_rating' => 'required|string',
            'base_game_id' => 'nullable|exists:games,id',
            
            // Dados de Relacionamento (EXISTENTES)
            'developer_id' => 'nullable|exists:developers,id',
            'publisher_id' => 'nullable|exists:publishers,id',
            'categories' => 'nullable|array', 
            'categories.*' => 'exists:categories,id',
            
            // Dados de Relacionamento (NOVOS) - Devem ser únicos
            'new_developer' => 'nullable|string|max:255|unique:developers,name',
            'new_publisher' => 'nullable|string|max:255|unique:publishers,name',
            'new_category' => 'nullable|string|max:255|unique:categories,name',
            
            // Dados das Imagens da Galeria (Opcional)
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ], [
            // Mensagens customizadas (opcional)
            'new_developer.unique' => 'Este desenvolvedor já existe. Selecione-o na lista.',
            'new_publisher.unique' => 'Este editor já existe. Selecione-o na lista.',
            'new_category.unique' => 'Esta categoria já existe. Selecione-a na lista.',
        ]);

        // 2. Iniciar uma Transação (Tudo ou Nada)
        DB::beginTransaction();

        try {
            
            $gameData = $request->only('title', 'about', 'release_date', 'age_rating');

            $gameData['base_game_id'] = $request->base_game_id;
            // --- Lógica do Desenvolvedor ---
            if ($request->filled('new_developer')) {
                $developer = Developer::create(['name' => $request->new_developer]);
                $gameData['developer_id'] = $developer->id;
            } else {
                $gameData['developer_id'] = $request->developer_id;
            }

            // --- Lógica do Editor ---
            if ($request->filled('new_publisher')) {
                $publisher = Publisher::create(['name' => $request->new_publisher]);
                $gameData['publisher_id'] = $publisher->id;
            } else {
                $gameData['publisher_id'] = $request->publisher_id;
            }

            // --- Lógica do Upload da Capa ---
            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')->store('game_covers', 'public');
                $gameData['cover_url'] = $coverPath;
            }
            
            // 4. Criar o Jogo
            $game = Game::create($gameData);

            // --- Lógica das Categorias ---
            $categoryIds = $request->categories ?? []; 
            if ($request->filled('new_category')) {
                $newCategory = Category::create([
                    'name' => $request->new_category,
                    'slug' => Str::slug($request->new_category) 
                ]);
                $categoryIds[] = $newCategory->id;
            }
            if (!empty($categoryIds)) {
                $game->categories()->attach($categoryIds);
            }

            // --- Lógica das Imagens da Galeria ---
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('game_images', 'public');
                    $game->images()->create(['image_url' => $path]);
                }
            }
            
            // 6. Confirmar tudo
            DB::commit();

        } catch (\Exception $e) {
            // 7. Se algo der errado, desfazer tudo
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao salvar o jogo: ' . $e->getMessage())->withInput();
        }

        // 8. Sucesso!
        return redirect()->route('admin.games.index')
            ->with('success', 'Jogo "' . $game->title . '" criado com sucesso!');
    }


    /**
     * Mostra o formulário para editar um jogo existente.
     */
    public function edit(Game $game)
    {
        // Pré-carrega as categorias que o jogo já tem
        $game->load('categories');
        
        // Busca os dados para os dropdowns
        $developers = Developer::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $baseGames = Game::whereNull('base_game_id')
        ->where('id', '!=', $game->id)
        ->orderBy('title')
        ->get();

        return view('admin.games.edit', [
            'game' => $game,
            'developers' => $developers,
            'publishers' => $publishers,
            'categories' => $categories,
            'baseGames' => $baseGames
        ]);
    }

    /**
     * Atualiza um jogo existente no banco de dados.
     * (Nota: Esta é uma versão simplificada, sem a lógica de "criar novo".)
     */
    public function update(Request $request, Game $game)
    {
        // 1. Validação (Nota: 'cover_image' é 'nullable' na atualização)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'about' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Opcional
            'release_date' => 'required|date',
            'age_rating' => 'required|string',
            'base_game_id' => 'nullable|exists:games,id',
            'developer_id' => 'required|exists:developers,id',
            'publisher_id' => 'required|exists:publishers,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            $gameData = $request->except('categories', 'cover_image');

            // 2. Lida com a atualização da imagem de capa (se uma nova foi enviada)
            if ($request->hasFile('cover_image')) {
                // Deleta a capa antiga do storage
                if ($game->cover_url) {
                    Storage::disk('public')->delete($game->cover_url);
                }
                // Salva a nova capa
                $coverPath = $request->file('cover_image')->store('game_covers', 'public');
                $gameData['cover_url'] = $coverPath;
            }

            // 3. Atualiza os dados do Jogo
            $game->update($gameData);

            // 4. Sincroniza as categorias
            // sync() é a forma correta para 'update': ele remove as antigas e adiciona as novas
            $game->categories()->sync($request->categories);
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao atualizar o jogo: ' . $e->getMessage())->withInput();
        }
        
        return redirect()->route('admin.games.index')->with('success', 'Jogo atualizado com sucesso!');
    }

    /**
     * Deleta um jogo do banco de dados.
     */
    public function destroy(Game $game)
    {
        DB::beginTransaction();
        try {
            // 1. Deleta a imagem de capa do storage
            if ($game->cover_url) {
                Storage::disk('public')->delete($game->cover_url);
            }
            
            // 2. Deleta todas as imagens da galeria do storage
            // (Carrega o relacionamento 'images' se ainda não estiver carregado)
            foreach ($game->images as $image) {
                Storage::disk('public')->delete($image->image_url);
                // (O 'delete()' do jogo abaixo vai apagar o registro da imagem no DB)
            }

            // 3. Deleta o registro do Jogo
            // O 'onDelete('cascade')' nas migrations vai apagar:
            // - as entradas em 'category_game'
            // - as entradas em 'game_images'
            // - as entradas em 'products' (e 'game_keys', 'system_requirements')
            $game->delete();
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.games.index')->with('error', 'Erro ao deletar o jogo.');
        }

        return redirect()->route('admin.games.index')->with('success', 'Jogo deletado com sucesso.');
    }
}