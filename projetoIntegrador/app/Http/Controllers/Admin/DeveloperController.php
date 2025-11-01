<?php

namespace App\Http\Controllers\Admin;

use App\Models\Developer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function create(){
        return view('admin.developers.create');
    }
    public function store(Request $request){
        $validated = $request->validate(['name' => 'required|string|max:255|unique:developers,name',]);

        Developer::create($validated);
        
        return redirect()->back()->with('success', 'Desenvolvedor adicionado!');
    }
}
