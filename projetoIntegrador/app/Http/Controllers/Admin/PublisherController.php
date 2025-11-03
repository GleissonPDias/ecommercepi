<?php

namespace App\Http\Controllers\Admin;

use App\Models\Publisher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PublisherController extends Controller
{
    public function create(){
        return view('admin.publishers.create');
    }
    public function store(Request $request){
        $validated = $request -> validate([
            'name' => 'required|string|unique:publishers,name',
        ]);

        Publisher::create($validated);

        return redirect()->route('admin.publishers.index')->with('success', 'Publisher adicionada com sucesso!');
    }
        public function index(){
        $publishers = Publisher::latest()->get();

        return view('admin.publishers.index', [
            'publishers' => $publishers
        ]);
    }
    public function edit(Publisher $publisher){
        return view('admin.publishers.edit', [
            'publisher' => $publisher
        ]);
    }
    public function update(Publisher $publisher, Request $request){
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('publishers')->ignore($publisher->id),
            ]
        ]);

        $publisher->update($validated);
        return redirect()->route('admin.publishers.index')->with('success', 'Atualizado com sucesso!');
    }
    public function destroy(Publisher $publisher){
        $publisher->delete();
        return redirect()->route('admin.publishers.index')
                     ->with('success', 'Publicadora deletada!');
    }
}


