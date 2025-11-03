<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Product $product){
        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'Você precisa estar logado para favoritar'); 
        }

        Auth::user()->favorites()->toggle($product->id);

        return back()->with('success', 'Favoritado');

    }

    public function index(){
        $products = Auth::user()->favorites()->with('game', 'platform')->get();

        return view('favorites.index', [
            'products' => $products
        ]);
    }
}
