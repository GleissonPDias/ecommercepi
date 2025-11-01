<?php

namespace App\Http\Controllers\Admin;

use App\Models\Publisher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        return redirect()->back()->with('success', 'Publisher adicionada com sucesso!');
    }
}
