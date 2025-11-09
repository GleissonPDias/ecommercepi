<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GettStore - Loja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    {{-- CSS Básico para os botões não quebrarem o layout --}}
    <style>
        .game-card {
            position: relative;
        }
        .game-card-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }
        .form-favorite-toggle, .form-cart-store {
            position: absolute;
            z-index: 10;
        }
        .form-favorite-toggle {
            top: 10px;
            right: 10px;
        }
        .form-cart-store {
            bottom: 85px; /* Ajuste este valor para o seu design */
            right: 10px;
        }
        .form-favorite-toggle button, .form-cart-store button {
            background: rgba(0,0,0,0.5);
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        .form-favorite-toggle button i.fas {
            color: red;
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-left">
            <button class="btn-menu" type="button" aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </button>
            {{-- CORRIGIDO: Link do logo --}}
            <a href="{{ route('home') }}"><img src="{{ asset('images/logo.svg') }}" alt="logo" class="logo"></a>
        </div>
<div class="header-center">
    {{-- 1. Transformado num formulário que envia para a rota 'search.index' --}}
    <form action="{{ route('search.index') }}" method="GET" class="search-bar">
        
        {{-- 2. Adicionado o atributo 'name="query"' --}}
        <input type="text" 
               name="query" 
               placeholder="Buscar jogo ou palavra-chave" 
               required 
               value="{{ request('query') ?? '' }}"> {{-- (Mantém o texto na barra) --}}
        
        {{-- 3. O ícone agora é um botão de 'submit' --}}
        <button type="submit" class="search-button" aria-label="Buscar">
            <i class="fas fa-search"></i>
        </button>
    </form>
</div>
        <div class="header-right">
            <div class="cart-icon">
                {{-- CORRIGIDO: Link do carrinho --}}
                <a href="{{ route('cart.index') }}"><i class="fas fa-shopping-cart"></i></a>
                <span class="cart-count">0</span> {{-- (Isto pode ser atualizado dinamicamente) --}}
            </div>
            
            @auth {{-- Mostra apenas se o usuário estiver logado --}}
                {{-- CORRIGIDO: Link do perfil --}}
                <a href="{{ route('profile.edit') }}"><i class="fas fa-user"></i></a>
                
                {{-- ADICIONADO: Botão de Logout --}}
                <form method="POST" action="{{ route('logout') }}" style="display: inline-flex; align-items: center; margin-left: 10px;">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       title="Sair"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt" style="color: white; font-size: 1.2rem;"></i>
                    </a>
                </form>
            @else {{-- Mostra se for visitante --}}
                <a href="{{ route('login') }}" style="color: white; text-decoration: none; margin-left: 10px;">Entrar</a>
            @endauth
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-header">
            <button class="btn-close" type="button" aria-label="Fechar menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        {{-- (Seu conteúdo de sidebar.links) --}}
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

    <main class="store-page">
<div class="featured-carousel-container">

    @foreach ($carouselSlides as $slide)
        
        @php
            $largeProduct = $slide->products->firstWhere('pivot.slot', 'large');
            $small1Product = $slide->products->firstWhere('pivot.slot', 'small_1');
            $small2Product = $slide->products->firstWhere('pivot.slot', 'small_2');
        @endphp

        <section class="featured-games-slide {{ $loop->first ? 'active' : '' }}">
                
            <div class="featured-main" style="position: relative;">
                @if ($largeProduct)
                    <a href="{{ route('products.show', $largeProduct) }}">
                        <img src="{{ Storage::url($largeProduct->game->cover_url) }}" alt="{{ $largeProduct->name }}">
                    </a>
                    <div class="price-overlay">
                        <i class="fas fa-shopping-cart"></i>
                        <span>R$ {{ $largeProduct->current_price }}</span>
                    </div>
                    
                    {{-- ADICIONADO: Botão de Favoritar --}}
                    <form method="POST" action="{{ route('favorites.toggle', $largeProduct) }}" class="form-favorite-toggle" 
                          style="position: absolute; top: 20px; right: 20px; z-index: 10;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.8rem; text-shadow: 0 0 5px black;">
                            @if(auth()->user() && auth()->user()->favorites->contains($largeProduct))
                                <i class="fas fa-heart" style="color: red;"></i>
                            @else
                                <i class="far fa-heart"></i>
                            @endif
                        </button>
                    </form>

                @else
                    <a href="#"><img src="{{ asset('images/placeholder-large.png') }}" alt="Produto destaque"></a>
                @endif
            </div>
            
            <div class="featured-sidebar">
                @if ($small1Product)
                    <div class="side-game-card" style="position: relative;">
                        <a href="{{ route('products.show', $small1Product) }}">
                            <img src="{{ Storage::url($small1Product->game->cover_url) }}" alt="{{ $small1Product->name }}">
                        </a>
                        
                        {{-- ADICIONADO: Botão de Favoritar --}}
                        <form method="POST" action="{{ route('favorites.toggle', $small1Product) }}" class="form-favorite-toggle" 
                              style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.5rem; text-shadow: 0 0 5px black;">
                                @if(auth()->user() && auth()->user()->favorites->contains($small1Product))
                                    <i class="fas fa-heart" style="color: red;"></i>
                                @else
                                    <i class="far fa-heart"></i>
                                @endif
                            </button>
                        </form>
                    </div>
                @endif
                
                @if ($small2Product)
                    <div class="side-game-card" style="position: relative;">
                        <a href="{{ route('products.show', $small2Product) }}">
                            <img src="{{ Storage::url($small2Product->game->cover_url) }}" alt="{{ $small2Product->name }}">
                        </a>
                        
                        {{-- ADICIONADO: Botão de Favoritar --}}
                        <form method="POST" action="{{ route('favorites.toggle', $small2Product) }}" class="form-favorite-toggle" 
                              style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.5rem; text-shadow: 0 0 5px black;">
                                @if(auth()->user() && auth()->user()->favorites->contains($small2Product))
                                    <i class="fas fa-heart" style="color: red;"></i>
                                @else
                                    <i class="far fa-heart"></i>
                                @endif
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </section>
    @endforeach
        
    <button class="carousel-arrow prev" aria-label="Slide anterior"><i class="fas fa-chevron-left"></i></button>
    <button class="carousel-arrow next" aria-label="Próximo slide"><i class="fas fa-chevron-right"></i></button>
    
    <div class="pagination-dots">
        @foreach ($carouselSlides as $slide)
             <span class="dot {{ $loop->first ? 'active' : '' }}" data-slide="{{ $loop->index }}"></span>
        @endforeach
    </div>

</div>

<section class="popular-games">
    <h2>Mais Populares</h2>
    <div class="games-carousel"> 
    @foreach ($products as $product)
        
        {{-- CORRIGIDO: O card agora é um DIV para conter os formulários --}}
        <div class="game-card">
            
            {{-- O link agora é uma classe separada --}}
            <a href="{{ route('products.show', $product) }}" class="game-card-link">
                <img src="{{ Storage::url($product->game->cover_url) }}" alt="{{ $product->name }}">
                <div class="game-info">
                    <h3>{{$product->name}}</h3>
                    <p class="game-platform">{{$product->platform->name}}</p>
                    <div class="price-info">
                        <span class="discount-tag">-82%</span> {{-- (Tornar dinâmico no futuro) --}}
                        <span class="price-tag">R$ {{$product->current_price}}</span>
                    </div>
                </div>
            </a>
            
            {{-- CORRIGIDO: 'lass=' para 'class=' e posicionado --}}
            <form method="POST" action="{{ route('favorites.toggle', $product) }}" class="form-favorite-toggle">
                @csrf
                <button type="submit">
                    @if(auth()->user() && auth()->user()->favorites->contains($product))
                        <i class="fas fa-heart"></i> {{-- Cheio --}}
                    @else
                        <i class="far fa-heart"></i> {{-- Vazio --}}
                    @endif
                </button>
            </form>
            
            {{-- CORRIGIDO: Formulário do carrinho posicionado --}}
            <form method="POST" action="{{ route('cart.store', $product) }}" class="form-cart-store">
                @csrf
                <button type="submit" class="btn-add-to-cart">
                    <i class="fas fa-shopping-cart"></i>
                </button>
            </form>

        </div> {{-- Fim do .game-card --}}

    @endforeach
    </div>
</section>

    </main>
    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-columns">
                {{-- (Suas colunas do footer) --}}
                <div class="footer-column">...</div>
                <div class="footer-column">...</div>
                <div class="footer-column">...</div>
            </div>
            <hr class="footer-divider">
            <div class="footer-bottom">
                {{-- CORRIGIDO: Link do footer --}}
                <a href="{{ route('home') }}"><img src="{{ asset('images/GettStore Branco s fundo.png') }}" alt="GettStore Avatar Logo" class="footer-logo"></a>
                <p class="footer-legal">
                    GettStore Ltda. - CNPJ 00.000.000/0000-00<br>
                    Rua Lauro Müller, nº 116, sala 503 - Torre do Rio Sul - Botafogo - Rio de Janeiro, RJ – 22290-160
                </p>
            </div>
        </div>
    </footer>
    </body>
</html>