<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product.game')->get();

        $subtotal = $cartItems->sum(function($item){
            return $item->quantity * $item->product->current_price;
        });

        return view('cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal
        ]);
    }

    public function store(Request $request, Product $product){

        $user = Auth::user();

        $existingItem = $user->cartItems()->where('product_id', $product->id)->first();

        if($existingItem){
            $existingItem->increment('quantity');
        } else {
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => 1,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produto adicionado ao carrinho com sucesso!');
        
    }

    public function destroy(CartItem $cartItem){
        if($cartItem->user_id !== Auth::id()){
            abort(403);
        }

        $cartItem->delete();
        return back()->with('success', 'Produto removido do carrinho!');
    }
}
