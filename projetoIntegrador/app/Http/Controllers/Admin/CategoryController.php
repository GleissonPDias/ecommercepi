<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        
        $slug = Str::slug($validated['name']);

        $dataToCreate = [
            'name' => $validated['name'],
            'slug' => $slug
        ];

        Category::create($dataToCreate);


        return redirect()->route('admin.categories.index')->with('success', 'Categoria adicionada com sucesso!');
    }
    public function index(){
        $categories = Category::latest()->get();
        return view('admin.categories.index', [
            'categories' => $categories
        ]);
    }
    public function edit(Category $category){
        return view('admin.categories.edit', [
            'category' => $category
        ]);
    }
        public function update(Category $category, Request $request){
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ]
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Atualizado com sucesso!');
    }
    public function destroy(Category $category){
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Categoria deletada!');
    }
}
