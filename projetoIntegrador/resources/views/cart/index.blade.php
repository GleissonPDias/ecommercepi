<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GettStore - Meu Carrinho</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>


    <header class="main-header">
        <div class="header-left">
            <button class="btn-menu" type="button" aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('home') }}"><img src="{{ asset('images/logo.svg') }}" alt="logo" class="logo"></a>
        </div>
        <div class="header-right">
            <div class="cart-icon">
                
                <a href="{{route('cart.index')}}"><i class="fas fa-shopping-cart active"></i></a>
                <span class="cart-count">{{$cartItems->count()}}</span> 
            </div>
            <a href="{{route('profile.edit') }}"> <i class="fas fa-user"></i></a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline-flex; align-items: center; margin-left: 10px;">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       title="Sair"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt" style="color: white; font-size: 1.2rem;"></i>
                    </a>
            </form>
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-header">
            <button class="btn-close" type="button" aria-label="Fechar menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <ul class="sidebar-links">
            <li><a href="#"><i class="fas fa-gamepad"></i> Catálogo</a></li>
            <li><a href="#"><i class="fas fa-tags"></i> Ofertas</a></li>
            <li><a href="#"><i class="fas fa-gift"></i> Gift Card</a></li>
            <li class="divider"></li>
            <li><a href="#"><i class="fas fa-desktop"></i> PC</a></li>
            <li><a href="#"><i class="fab fa-xbox"></i> Xbox</a></li>
            <li><a href="#"><i class="fab fa-playstation"></i> Playstation</a></li>
            <li><a href="#"><i class="fas fa-gamepad"></i> Switch</a></li>
            <li class="divider"></li>
            <li><a href="#"><i class="fas fa-headset"></i> Suporte</a></li>
            <li><a href="#"><i class="fas fa-ellipsis-h"></i> Mais</a></li>
        </ul>
    </aside>

    <div class="overlay"></div>

    <main class="cart-page">
        <div class="cart-container">
            
            <section class="cart-summary">

                <a href="{{route('home')}}" class="continue-shopping">
                    <i class="fas fa-chevron-left"></i>
                    Continuar Comprando
                </a>
                
                <div class="cart-header">
                    <h2>Meu Carrinho</h2>
                </div>

                @if (session('success'))
                 <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                {{ session('success') }}
                </div>
                @endif
                @if (session('error'))
    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
        <strong>Erro:</strong> {{ session('error') }}
    </div>
                @endif

                <div class="cart-item-list">
                    <span class="list-header">Produto</span>
                    @forelse ($cartItems as $item)
                    <div class="cart-item">
                        <img src="{{Storage::url($item->product->game->cover_url)}}" alt="{{$item->product->name}}" class="item-image"> 
                        <div class="item-details">
                            <a href="{{route('products.show', $item->product)}}">
                                <h4>{{$item->product->name}}</h4>
                            </a>
                            
                            <p>{{$item->product->platform->name}} (por chave de ativação)</p>
                        </div>
                                                <div class="item-quantity">
                            {{-- Botão de diminuir --}}
                            <form action="{{ route('cart.decrease', $item) }}" method="POST" class="quantity-form">
                                @csrf
                                <button type="submit" class="btn-quantity">-</button>
                            </form>
                            
                            {{-- O número --}}
                            <span class="quantity-value">{{ $item->quantity }}</span>

                            {{-- Botão de aumentar --}}
                            <form action="{{ route('cart.increase', $item) }}" method="POST" class="quantity-form">
                                @csrf
                                <button type="submit" class="btn-quantity">+</button>
                            </form>
                        </div>
                        <div class="item-price">
                            <span class="old-price">R$ {{ number_format($item->product->default_price * $item->quantity, 2, ',', '.') }}</span>
                            <span class="new-price">R$ {{ number_format($item->product->current_price * $item->quantity, 2, ',', '.') }}</span>
                        </div>
                        <form action="{{route('cart.destroy', $item)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="item-remove" aria-label="Remover item">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="cart-item" style="justify-content: center; padding:20px;">
                        <p>Seu carrinho está vazio.</p>
                    </div>
                    @endforelse
                </div>

                <div class="coupon-section">
    <h4>Possui um cupom de desconto ou voucher?</h4>

    {{-- Formulário para APLICAR o cupão --}}
    <form action="{{ route('coupon.apply') }}" method="POST" class="coupon-apply">
        @csrf
        <input type="text" name="code" placeholder="Cupom ou voucher" required>
        <button type="submit">Aplicar</button>
    </form>
</div>

                <div class="info-box">
                    <h4><i class="fas fa-info-circle"></i> Informações Importantes:</h4>
                    <ul>
                        <li>Todos os itens são entregues apenas de forma digital por download e estão sujeitos à política de reembolso.</li>
                        <li>Verifique os requisitos de sistema na página de cada jogo e os Termos de Uso antes de realizar a compra.</li>
                    </ul>
                </div>

            </section>

            <aside class="payment-sidebar">

                <div class="payment-methods">
                    <h3>Formas de Pagamento</h3>

                    <div class="payment-option">
                        <input type="radio" id="credito-vista" name="payment-method" checked>
                        <label for="credito-vista">
                            Cartão de Crédito - À Vista
                            <span>Pague à vista com cartão de crédito</span>
                            <div class="card-icons">
                                <i class="fab fa-cc-visa"></i>
                                <i class="fab fa-cc-mastercard"></i>
                                <i class="fab fa-cc-amex"></i>
                                <i class="fab fa-cc-diners-club"></i>
                            </div>
                        </label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="credito-parcelado" name="payment-method">
                        <label for="credito-parcelado">
                            Cartão de Crédito - Parcelado
                            <span>Parcele em até 3x sem juros</span>
                            <div class="card-icons">
                                <i class="fab fa-cc-visa"></i>
                                <i class="fab fa-cc-mastercard"></i>
                                <i class="fab fa-cc-amex"></i>
                                <i class="fab fa-cc-diners-club"></i>
                            </div>
                        </label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="pix" name="payment-method">
                        <label for="pix">PIX</label>
                    </div>

                    <div class="payment-option">
                        <input type="radio" id="boleto" name="payment-method">
                        <label for="boleto">Boleto</label>
                    </div>
                </div>

<div class="price-summary">
    {{-- MOSTRA O SUBTOTAL --}}
    <div class="summary-row">
        <span>Subtotal</span>
        <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
    </div>

    {{-- MOSTRA O DESCONTO (APENAS SE EXISTIR) --}}
    @if (session('coupon'))
        <div class="summary-row" style="color: #28a745;"> {{-- (cor verde) --}}
            <span>Desconto ({{ session('coupon')->code }})</span>
            <span>- R$ {{ number_format($discountAmount, 2, ',', '.') }}</span>

            {{-- Formulário para REMOVER o cupão --}}
            <form action="{{ route('coupon.remove') }}" method="POST" style="text-align: right; margin-top: 5px;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #ff4d4d; cursor: pointer; padding: 0; font-size: 0.8em;">
                    [Remover cupão]
                </button>
            </form>
        </div>
    @endif

    {{-- MOSTRA O NOVO TOTAL --}}
    <div class="summary-row total">
        <span>Valor total</span>
        <div>
            {{-- Usamos a nova variável $total --}}
            <span class="new-total">R$ {{ number_format($total, 2, ',', '.') }}</span>
        </div>
    </div>
</div>
                {{-- 
  Este formulário envia para a rota 'order.store'
  e aciona o OrderController que acabámos de criar.
--}}
<form action="{{ route('order.store') }}" method="POST">
    @csrf
    
    {{-- 
      (Aqui você pode adicionar <input type="hidden"> 
      para enviar o 'payment-method' que o usuário selecionou)
    --}}
    
    <button type="submit" class="btn-continue" style="width: 100%; border: none; font-size: 1rem; cursor: pointer;">
        Finalizar e Pagar
    </button>
</form>

            </aside>

        </div>
    </main>
    
    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-columns">
                <div class="footer-column">
                    <h3>Seguir GettStore</h3>
                    <div class="social-icons">
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                <div class="footer-column">
                    <h3>Institucional</h3>
                    <ul>
                        <li><a href="#">Sobre</a></li>
                        <li><a href="#">Carreiras</a></li>
                        <li><a href="#">Seu jogo na Nuuvem</a></li>
                        <li><a href="#">Segurança</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Ajuda</h3>
                    <ul>
                        <li><a href="#">Suporte</a></li>
                        <li><a href="#">Termos de Uso</a></li>
                        <li><a href="#">Política de Privacidade</a></li>
                    </ul>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="footer-bottom">
                <a href="{{ route('home') }}"><img src="{{ asset('images/GettStore1.png') }}" alt="GettStore Avatar Logo" class="footer-logo"></a>
                <p class="footer-legal">
                    GettStore Ltda. – CNPJ 00.000.000/0000-00<br>
                    Rua Lauro Müller, nº 116, sala 503 - Torre do Rio Sul - Botafogo - Rio de Janeiro, RJ – 22290-160
                </p>
            </div>
        </div>
    </footer>

</body>
</html>