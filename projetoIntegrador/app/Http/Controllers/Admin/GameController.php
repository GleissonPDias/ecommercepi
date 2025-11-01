<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Game;

class GameController extends Controller
{
    public function create(){

        $developers = Developer::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        return view('admin.games.create', [
            'developers' => $developers,
            'publishers' => $publishers
        ]);
    }
    public function store(Request $request){
        $validated = $request -> validate([
            'title' => 'required|string|max:255',
            'cover_url' => 'required|string',
            'release_date' => 'required|date',
            'age_rating' => 'required|string',
            'about' => 'required|string',
            'developer_id' => 'required|exists:developers,id',
            'publisher_id' => 'required|exists:publishers,id',
        ]);

        Game::create($validated);

        return redirect()->back()->with('success', 'Jogo adicionado com sucesso!');
    }
}
