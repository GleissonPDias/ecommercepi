<?php

namespace App\Http\Controllers;

// Todos os 'use' necessários
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\GameKey;
use App\Models\Coupon;
use App\Models\PaymentMethod; // 👈 Importa o PaymentMethod
use Illuminate\Support\Facades\Storage; // 👈 Importa o Storage
use Illuminate\Support\Facades\Log;     // 👈 Importa o Log (para registar erros)
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentMethod as StripePaymentMethod;

class PaymentController extends Controller
{
    // O __construct() foi REMOVIDO. A proteção está nas rotas (web.php).

    /**
     * PASSO 1: Prepara o checkout e redireciona para o Stripe.
     * Isto é acionado pelo seu botão "Finalizar e Pagar".
     */
    public function redirectToCheckout(Request $request)
    {
        // Define a chave da API do Stripe para este método
        // (Isto corrige o erro "No API key provided")
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product.game')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'O seu carrinho está vazio.');
        }

        // --- 1. Calcular o Total (com cupão) ---
        $subtotal = $cartItems->sum(fn($i) => $i->quantity * $i->product->current_price);
        $totalAmount = $subtotal;
        $coupon = session('coupon');
        $stripeCouponId = null;

        if ($coupon) {
            $discountAmount = 0;
            if ($coupon->type === 'percentage') $discountAmount = $subtotal * ($coupon->value / 100);
            if ($coupon->type === 'fixed') $discountAmount = $coupon->value;
            $totalAmount = $subtotal - min($subtotal, $discountAmount);
            
            try {
                // Tenta criar um cupão no Stripe
                $stripeCoupon = \Stripe\Coupon::create([
                    'amount_off' => (int) ($discountAmount * 100), // Stripe usa centavos
                    'currency' => 'brl',
                    'duration' => 'once',
                ]);
                $stripeCouponId = $stripeCoupon->id;
            } catch (\Exception $e) { 
                Log::error('Erro ao criar cupão Stripe: ' . $e->getMessage());
                // Se falhar, continua sem o cupão, mas regista o erro
            }
        }
        
        // --- 2. Verificar Stock ANTES de ir para o pagamento ---
        try {
            foreach ($cartItems as $item) {
                $stock = GameKey::where('product_id', $item->product_id)
                                ->where('is_sold', false)
                                ->count();
                if ($stock < $item->quantity) {
                    throw new \Exception('Stock insuficiente para: ' . $item->product->name . '. Pedido: ' . $item->quantity . ', Disponível: ' . $stock);
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Erro: ' . $e->getMessage());
        }

        // --- 3. Formatar Itens para o Stripe ---
        $line_items = [];
        foreach ($cartItems as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'brl', 
                    'product_data' => [
                        'name' => $item->product->name,
                        // CORRIGIDO: A linha 'images' foi removida
                        // para evitar o erro "Not a valid URL" em localhost.
                    ],
                    'unit_amount' => (int) ($item->product->current_price * 100), // Preço em CENTAVOS
                ],
                'quantity' => $item->quantity,
            ];
        }

        // --- 4. Criar a Sessão de Checkout do Stripe ---
        try {
            $checkout_session = StripeSession::create([
                'payment_method_types' => ['card', 'boleto'], 
                'line_items' => $line_items,
                'discounts' => $stripeCouponId ? [['coupon' => $stripeCouponId]] : [],
                'mode' => 'payment',
                'customer_email' => $user->email, 
                // Lógica de "Salvar Cartão"
                'payment_intent_data' => [
                    'setup_future_usage' => 'on_session', 
                ],
                'client_reference_id' => $user->id, // Guarda o ID do nosso utilizador
                
                // Rotas de retorno
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel'),
            ]);

            session(['stripe_session_id' => $checkout_session->id]);
            
            // 5. Redireciona o utilizador para a página de pagamento do Stripe
            return redirect($checkout_session->url);

        } catch(\Exception $e) {
            // Se falhar (ex: chave de API errada), volta ao carrinho com o erro
            return redirect()->route('cart.index')->with('error', 'Erro ao contactar o gateway de pagamento: ' . $e->getMessage());
        }
    }

    /**
     * PASSO 2: O Stripe redireciona para cá APÓS o pagamento.
     * É AQUI que o seu Pedido (Order) é finalmente criado.
     */
    public function handleSuccess(Request $request)
    {
        // Define a chave da API do Stripe para este método também
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        $user = Auth::user();
        
        // 1. Validar a sessão do Stripe
        $stripeSessionId = $request->query('session_id');
        if (!$stripeSessionId) {
             return redirect()->route('cart.index')->with('error', 'Sessão de pagamento não encontrada.');
        }
        
        session()->forget('stripe_session_id');

        // 2. Buscar os detalhes da sessão (para salvar o cartão)
        try {
            $session = StripeSession::retrieve($stripeSessionId);
            
            if ($session->payment_intent) {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
                $paymentMethodId = $paymentIntent->payment_method;
                
                if ($paymentMethodId && is_string($paymentMethodId)) {
                    $paymentMethod = StripePaymentMethod::retrieve($paymentMethodId);

                    if ($paymentMethod->type == 'card') {

                        // --- 👇 INÍCIO DA CORREÇÃO (Sincronizado com a sua Migração) 👇 ---
                        
                        $isFirstCard = $user->paymentMethods()->count() == 0;

                        // Salva na sua tabela 'payment_methods' (com os nomes corretos)
                        $user->paymentMethods()->updateOrCreate(
                            [
                                // Procura por 'gateway_token' (da sua migração)
                                'gateway_token' => $paymentMethodId, 
                            ],
                            [
                                // Salva 'card_brand' (da sua migração)
                                'card_brand' => $paymentMethod->card->brand, 
                                // Salva 'last_four_digits' (da sua migração)
                                'last_four_digits' => $paymentMethod->card->last4, 
                                'is_default' => $isFirstCard,
                            ]
                        );
                        // --- 👆 FIM DA CORREÇÃO 👆 ---
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Erro ao salvar o cartão do Stripe: ' . $e->getMessage());
        }

        // --- 3. A LÓGICA DE CRIAR A ORDEM ---
        
        $cartItems = $user->cartItems()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('profile.edit')->with('success', 'O seu pedido já foi processado!');
        }

        $coupon = session('coupon');
        $subtotal = $cartItems->sum(fn($i) => $i->quantity * $i->product->current_price);
        $totalAmount = $subtotal;
        $couponId = null;
        if ($coupon) {
            $discountAmount = 0;
            if ($coupon->type === 'percentage') $discountAmount = $subtotal * ($coupon->value / 100);
            if ($coupon->type === 'fixed') $discountAmount = $coupon->value;
            $totalAmount = $subtotal - min($subtotal, $discountAmount);
            $couponId = $coupon->id;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'coupon_id' => $couponId,
            ]);

            foreach ($cartItems as $item) {
                $availableKeys = GameKey::where('product_id', $item->product_id)
                                    ->where('is_sold', false)
                                    ->lockForUpdate()
                                    ->take($item->quantity)
                                    ->get();
                if ($availableKeys->count() < $item->quantity) {
                     throw new \Exception('Stock esgotou durante o pagamento: ' . $item->product->name);
                }

                $orderItem = $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_at_purchase' => $item->product->current_price,
                ]);

                foreach ($availableKeys as $key) {
                    // CORRIGIDO: Remove 'order_id'
                    $key->update([
                        'is_sold' => true,
                        'user_id' => $user->id,
                        'order_item_id' => $orderItem->id, 
                    ]);
                }
            }

            $user->cartItems()->delete();
            session()->forget('coupon');
            //if ($couponId) Coupon::find($couponId)->increment('uses_count');
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Pagamento aprovado, mas erro ao atribuir chaves. Contacte o suporte. Erro: ' . $e->getMessage());
        }
        
        // SUCESSO!
        return redirect()->route('profile.edit')->with('success', 'Pagamento aprovado! A sua compra está em "Meus Games".');
    }

    /**
     * O Stripe redireciona para cá se o utilizador cancelar.
     */
    public function handleCancel()
    {
        return redirect()->route('cart.index')->with('error', 'O seu pagamento foi cancelado.');
    }

    /**
     * Remove um método de pagamento salvo.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }
        
        $paymentMethod->delete();
        
        return back()->with('success', 'Método de pagamento removido.');
    }
}