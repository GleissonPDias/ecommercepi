<?php

namespace App\Http\Controllers\Admin;

use App\Models\Developer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeveloperController extends Controller
{
    public function create(){
        return view('admin.developers.create');
    }
    public function store(Request $request){
        $validated = $request->validate(['name' => 'required|string|max:255|unique:developers,name',]);

        Developer::create($validated);
        
        return redirect()->route('admin.developers.index')->with('success', 'Desenvolvedor adicionado!');
    }
    public function index(){
        $developers = Developer::latest()->get();

        return view('admin.developers.index', [
            'developers' => $developers
        ]);
    }
    public function edit(Developer $developer){
        return view('admin.developers.edit', [
            'developer' => $developer
        ]);
    }
    public function update(Developer $developer, Request $request){
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('developers')->ignore($developer->id),
            ]
        ]);

        $developer->update($validated);
        return redirect()->back()->with('success', 'Atualizado com sucesso!');
    }
    public function destroy(Developer $developer){
        $developer->delete();
        return redirect()->route('admin.developers.index')
                     ->with('success', 'Desenvolvedor deletado!');
    }
}
