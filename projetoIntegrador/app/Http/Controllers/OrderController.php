<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // 👈 Para a Transação
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\GameKey;
use App\Models\Coupon;
use App\Exceptions\OutOfStockException;
// (Pode precisar de: use App\Exceptions\OutOfStockException;)

class OrderController extends Controller
{
    // Garante que o utilizador esteja logado
    /**
     * Processa a compra, cria o Pedido (Order) e atribui as chaves.
     */
public function store(Request $request)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'O seu carrinho está vazio.');
        }

        DB::beginTransaction();

        try {
            // =======================================================
            // 👇 INÍCIO DA LÓGICA DE CÁLCULO DE CUPÃO (NOVA) 👇
            // =======================================================

            // 1. Calcular o Subtotal (o preço original)
            $subtotal = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->current_price;
            });

            // 2. Pega o cupão da sessão (se existir)
            $coupon = session('coupon');
            $discountAmount = 0;
            $couponId = null; // Para guardar no banco

            if ($coupon) {
                // 3. Recalcula o desconto
                if ($coupon->type === 'percentage') {
                    $discountAmount = $subtotal * ($coupon->value / 100);
                } elseif ($coupon->type === 'fixed') {
                    $discountAmount = $coupon->value;
                }
                
                $discountAmount = min($subtotal, $discountAmount); // Garante que não fica negativo
                $couponId = $coupon->id;
            }

            // 4. Calcula o TOTAL FINAL (com desconto)
            $totalAmount = $subtotal - $discountAmount;

            // =======================================================
            // 👆 FIM DA LÓGICA DE CÁLCULO DE CUPÃO 👆
            // =======================================================

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'completed', 
            ]);

            // --- INÍCIO DA LÓGICA CORRIGIDA ---

            // Loop 1: Processa CADA LINHA do carrinho (ex: Cyberpunk, Elden Ring)
            foreach ($cartItems as $item) {
                
                $quantityToBuy = $item->quantity; // Ex: 3

                // 6. 🛡️ VERIFICAR STOCK (CORRIGIDO) 🛡️
                //    Busca N chaves (onde N = quantidade)
                $availableKeys = GameKey::where('product_id', $item->product_id)
                                    ->where('is_sold', false)
                                    ->lockForUpdate()
                                    ->take($quantityToBuy) // <-- Pega a quantidade correta (ex: 3)
                                    ->get();

                // 7. 🛡️ VERIFICAÇÃO DE STOCK (MAIS FORTE) 🛡️
                //    Verifica se o número de chaves encontradas é o suficiente
                if ($availableKeys->count() < $quantityToBuy) {
                    // Falha a transação se não houver stock
                    throw new \Exception('Stock insuficiente para: ' . $item->product->name . 
                                       '. (Pedido: ' . $quantityToBuy . ', Disponível: ' . $availableKeys->count() . ')');
                }

                // 8. Cria o Item do Pedido (só uma linha, ex: 3x Cyberpunk)
                $orderItem = $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $quantityToBuy,
                    'price_at_purchase' => $item->product->current_price,
                ]);

                // 9. Loop 2: Atribui CADA UMA das chaves encontradas
                foreach ($availableKeys as $key) {
                    $key->update([
                        'is_sold' => true,
                        'user_id' => $user->id,
                        'order_item_id' => $orderItem->id,
                    ]);
                }
            } // --- Fim do Loop 1 (Cart Items) ---

            // 10. Se tudo correu bem -> Esvaziar o carrinho
            $user->cartItems()->delete();

            session()->forget('coupon');

            // 11. Confirmar tudo no banco de dados
            DB::commit();

        } catch (\Exception $e) {
            // 12. Se algo falhou (ex: falta de stock), desfaz TUDO.
            DB::rollBack();
            
            return redirect()->route('cart.index')->with('error', 'Erro: ' . $e->getMessage());
        }

        // 13. SUCESSO!
        return redirect()->route('profile.edit')
            ->with('success', 'Compra realizada com sucesso!');
    }
}