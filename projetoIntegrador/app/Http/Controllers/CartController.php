<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\GameKey;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;

class CartController extends Controller
{

    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product.game')->get();

        $subtotal = $cartItems->sum(function($item){
            return $item->quantity * $item->product->current_price;
        });

        $coupon = session('coupon');
        $discountAmount = 0;
        $total = $subtotal;

        if ($coupon) {
        // 3. Calcula o desconto
        if ($coupon->type === 'percentage') {
            $discountAmount = $subtotal * ($coupon->value / 100);
        } elseif ($coupon->type === 'fixed') {
            $discountAmount = $coupon->value;
        }

        // 4. Garante que o desconto não é maior que o subtotal
        $discountAmount = min($subtotal, $discountAmount);

        // 5. Calcula o novo total
        $total = $subtotal - $discountAmount;
    }


        return view('cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'discountAmount' => $discountAmount,
            'total' => $total,
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
public function increase(CartItem $cartItem)
    {
        // 1. Segurança: Só o dono do item pode aumentar
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. 🛡️ Verificar Stock 🛡️
        $stock = GameKey::where('product_id', $cartItem->product_id)
                        ->where('is_sold', false)
                        ->count();

        // Verifica se a nova quantidade (atual + 1) ultrapassa o stock
        if ($cartItem->quantity + 1 > $stock) {
            return back()->with('error', "Stock insuficiente. Só temos $stock chaves disponíveis para este produto.");
        }

        // 3. Se houver stock, aumenta a quantidade
        $cartItem->increment('quantity');

        return back(); // Redireciona de volta para o carrinho
    }

    /**
     * Diminui a quantidade de um item no carrinho.
     */
    public function decrease(CartItem $cartItem)
    {
        // 1. Segurança
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Lógica de diminuição
        if ($cartItem->quantity > 1) {
            // Se a quantidade for maior que 1, apenas diminui
            $cartItem->decrement('quantity');
        } else {
            // Se a quantidade for 1, clicar em '-' remove o item
            $cartItem->delete();
            return back()->with('success', 'Item removido do carrinho.');
        }

        return back(); // Redireciona de volta
    }
}
