<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\GameKey;
use App\Models\Coupon;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentMethod as StripePaymentMethod; // Para salvar o cartão

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Define a chave secreta do Stripe para todas as funções
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * PASSO 1: Prepara o checkout e redireciona para o Stripe.
     * Isto é acionado pelo seu botão "Finalizar e Pagar".
     */
    public function redirectToCheckout(Request $request)
    {
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('product')->get();

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
            
            // Tenta criar o cupão no Stripe
            try {
                $stripeCoupon = \Stripe\Coupon::create([
                    'amount_off' => (int) ($discountAmount * 100), // Stripe usa centavos
                    'currency' => 'brl',
                    'duration' => 'once',
                ]);
                $stripeCouponId = $stripeCoupon->id;
            } catch (\Exception $e) { /* Ignora se o cupão falhar */ }
        }
        
        // --- 2. Verificar Stock (o seu 'throw' do OrderController) ---
        try {
            foreach ($cartItems as $item) {
                $stock = GameKey::where('product_id', $item->product_id)
                                ->where('is_sold', false)
                                ->count();
                if ($stock < $item->quantity) {
                    throw new \Exception('Stock insuficiente para: ' . $item->product->name);
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
                    'currency' => 'brl', // Moeda (Real Brasileiro)
                    'product_data' => [
                        'name' => $item->product->name,
                        'images' => [Storage::url($item->product->game->cover_url)], // Mostra a imagem no checkout
                    ],
                    'unit_amount' => (int) ($item->product->current_price * 100), // Preço em CENTAVOS
                ],
                'quantity' => $item->quantity,
            ];
        }

        // --- 4. Criar a Sessão de Checkout do Stripe ---
        $checkout_session = StripeSession::create([
            'payment_method_types' => ['card', 'boleto'], // Aceita Cartão e Boleto
            'line_items' => $line_items,
            'discounts' => $stripeCouponId ? [['coupon' => $stripeCouponId]] : [],
            'mode' => 'payment',
            'customer_email' => $user->email, // Preenche o email
            
            // 👇 AQUI ESTÁ A LÓGICA DE "SALVAR CARTÃO" 👇
            // Diz ao Stripe que queremos guardar este cartão para uso futuro
            'payment_intent_data' => [
                'setup_future_usage' => 'on_session', 
            ],
            // Guarda o ID do nosso utilizador no Stripe para o encontrarmos mais tarde
            'client_reference_id' => $user->id, 
            
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
        ]);

        // Guarda o ID da sessão para verificar no 'success'
        session(['stripe_session_id' => $checkout_session->id]);

        // 6. Redireciona o utilizador para a página de pagamento
        return redirect($checkout_session->url);
    }

    /**
     * PASSO 2: O Stripe redireciona para cá APÓS o pagamento.
     * É AQUI que o seu Pedido (Order) é finalmente criado.
     */
    public function handleSuccess(Request $request)
    {
        $user = Auth::user();
        
        // 1. Validar a sessão do Stripe
        $stripeSessionId = $request->query('session_id');
        if (!$stripeSessionId || $stripeSessionId !== session('stripe_session_id')) {
            return redirect()->route('cart.index')->with('error', 'Sessão de pagamento inválida.');
        }
        
        // 2. Limpar a sessão para não ser usada de novo
        session()->forget('stripe_session_id');

        // 3. Buscar os detalhes da sessão (para salvar o cartão)
        try {
            $session = StripeSession::retrieve($stripeSessionId);
            
            // 4. 👇 SALVAR O CARTÃO (A SUA NOVA EXIGÊNCIA) 👇
            if ($session->payment_intent) {
                // Pega o ID do método de pagamento (ex: 'pm_123')
                $paymentIntent = \Stripe\PaymentIntent::retrieve($session->payment_intent);
                $paymentMethodId = $paymentIntent->payment_method;

                // Pega os detalhes do método de pagamento
                $paymentMethod = StripePaymentMethod::retrieve($paymentMethodId);

                // Salva na sua tabela 'payment_methods'
                $user->paymentMethods()->create([
                    'stripe_pm_id' => $paymentMethodId, // O ID do Stripe (para cobrar no futuro)
                    'brand' => $paymentMethod->card->brand, // Ex: "visa"
                    'last_four' => $paymentMethod->card->last4, // Ex: "4242"
                    'expires_at_month' => $paymentMethod->card->exp_month,
                    'expires_at_year' => $paymentMethod->card->exp_year,
                ]);
            }
        } catch (\Exception $e) {
            // Se falhar a salvar o cartão, não faz mal, a compra foi feita
            // Apenas regista o erro
            \Log::error('Erro ao salvar o cartão do Stripe: ' . $e->getMessage());
        }

        // --- 5. A SUA LÓGICA DE 'OrderController@store' ---
        
        $cartItems = $user->cartItems()->with('product')->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'O seu carrinho já foi processado.');
        }

        // Recalcula o total (com cupão) para guardar no pedido
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

        // Inicia a transação (isto é seguro, o pagamento já foi feito)
        DB::beginTransaction();
        try {
            // (A verificação de stock já foi feita antes de ir para o Stripe)
            
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $totalAmount,
                'status' => 'completed',
                'coupon_id' => $couponId,
            ]);

            foreach ($cartItems as $item) {
                // (Verificação de stock final, por segurança)
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
                    $key->update([
                        'is_sold' => true,
                        'user_id' => $user->id,
                        'order_item_id' => $orderItem->id,
                    ]);
                }
            }

            $user->cartItems()->delete();
            session()->forget('coupon');
            if ($couponId) Coupon::find($couponId)->increment('uses_count'); // Incrementa o uso do cupão
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // 🚨 ALERTA: O pagamento foi feito, mas a entrega das chaves falhou!
            return redirect()->route('cart.index')->with('error', 'O seu pagamento foi aprovado, mas houve um erro ao atribuir as suas chaves. Por favor, contacte o suporte.');
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
}