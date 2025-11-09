<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // 👈 Para a Transação
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\GameKey;
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

        // 1. Verificação de segurança
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'O seu carrinho está vazio.');
        }

        // 2. Inicia a Transação "Tudo-ou-Nada"
        DB::beginTransaction();

        try {
            // 3. Calcular o total (no backend, por segurança)
            $totalAmount = $cartItems->sum(function($item) {
                return $item->quantity * $item->product->current_price;
            });

            // 4. Criar o Pedido (Order)
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'completed', // Assumindo pagamento aprovado
            ]);

            // 5. Processar CADA item
            foreach ($cartItems as $item) {

                // 6. 🛡️ VERIFICAR STOCK DE CHAVES 🛡️
                $availableKey = GameKey::where('product_id', $item->product_id)
                                    ->where('is_sold', false)
                                    ->lockForUpdate() // Impede que 2 pessoas peguem a mesma key
                                    ->first();

                // 7. Se não houver chave, FALHA A TRANSAÇÃO
                if (!$availableKey) {
                    throw new \Exception('Produto fora de stock: ' . $item->product->name);
                }

                // 8. Copiar o item do carrinho para OrderItem (permanente)
                $orderItem = $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->current_price,
                ]);

                // 9. Atribuir a Chave (Key) ao utilizador e ao pedido
                $availableKey->update([
                    'is_sold' => true,
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id
                    // 'order_item_id' => $orderItem->id, // (Se tiver esta coluna)
                ]);
            }

            // 10. Se tudo correu bem -> Esvaziar o carrinho
            $user->cartItems()->delete();

            // 11. Confirmar tudo no banco de dados
            DB::commit();

        } catch (\Exception $e) {
            // 12. Se algo falhou (ex: falta de stock), desfaz TUDO.
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Erro: ' . $e->getMessage());
        }

        // 13. SUCESSO! Redireciona para "Meus Games"
        return redirect()->route('profile.edit') // (Vamos fazer 'meus-games' ser a aba ativa)
            ->with('success', 'Compra realizada com sucesso!');
    }
}