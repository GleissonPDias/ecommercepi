<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlatformController extends Controller
{
    public function create()
    {
        return view('admin.platforms.create');
    }

    public function store(Request $request)
    {
        // ERRO 1 CORRIGIDO: 'validate' (com T)
        // ERRO 2 CORRIGIDO: 'unique:platforms,name' (com vírgula)
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name',
        ]);
        
        $slug = Str::slug($validated['name']);

        $dataToCreate = [
            'name' => $validated['name'],
            'slug' => $slug
        ];

        // Isto agora vai funcionar por causa da correção no Model (abaixo)
        Platform::create($dataToCreate);

        // ERRO 3 CORRIGIDO: 'success' (com dois 'c's)
        return redirect()->route('admin.platforms.index')->with('success', 'Plataforma adicionada com sucesso!');
    }
    public function index(){
        $platforms = Platform::latest()->get();
        return view('admin.platforms.index', [
            'platforms' => $platforms
        ]);
    }
    public function edit(Platform $platform){
        return view('admin.platforms.edit', [
            'platform' => $platform
        ]);
    }
        public function update(Platform $platform, Request $request){
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('platforms')->ignore($platform->id),
            ]
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $platform->update($validated);
        return redirect()->route('admin.platforms.index')->with('success', 'Atualizado com sucesso!');
    }
    public function destroy(Platform $platform){
        $platform->delete();
        return redirect()->route('admin.platforms.index')->with('success', 'Categoria deletada!');
    }
}