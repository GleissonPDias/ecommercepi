

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
</head>
<body>

    <header class="main-header">
        <div class="header-left">
            <button class="btn-menu" type="button" aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </button>
            <a href="home.html"><img src="{{ asset('images/gettstore(1).png') }}" alt="logo" class="logo"></a>
        </div>
        <div class="header-center">
            <div class="search-bar">
                <input type="text" placeholder="Buscar jogo ou palavra-chave">
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="header-right">
            <div class="cart-icon">
                <a href="../carrinho/carrinho.html"><i class="fas fa-shopping-cart"></i></a>
                <span class="cart-count">0</span>
            </div>
            <a href="/contaUsuario/conta.html"><i class="fas fa-user"></i></a>
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

    <main class="store-page">
<div class="featured-carousel-container">

    {{-- 1. Loop externo para CADA SLIDE que o controller enviou --}}
    @foreach ($carouselSlides as $slide)
        
        {{-- Pega os produtos de CADA slot dentro do slide --}}
        @php
            $largeProduct = $slide->products->firstWhere('pivot.slot', 'large');
            $small1Product = $slide->products->firstWhere('pivot.slot', 'small_1');
            $small2Product = $slide->products->firstWhere('pivot.slot', 'small_2');
        @endphp

        {{-- 2. Cria a <section> do slide (só 'active' no primeiro) --}}
        <section class="featured-games-slide {{ $loop->first ? 'active' : '' }}">
            
            <div class="featured-main">
                {{-- 3. Exibe o produto 'large' deste slide --}}
                @if ($largeProduct)
                    <a href="{{ route('products.show', $largeProduct) }}">
                        <img src="{{ $largeProduct->game->cover_url }}" alt="{{ $largeProduct->name }}">
                    </a>
                    <div class="price-overlay">
                        <i class="fas fa-shopping-cart"></i>
                        <span>R$ {{ $largeProduct->current_price }}</span>
                    </div>
                    {{-- (etc) --}}
                @else
                    <a href="#"><img src="{{ asset('images/placeholder-large.png') }}" alt="Produto destaque"></a>
                @endif
            </div>

            <div class="featured-sidebar">
                {{-- 4. Exibe o produto 'small_1' deste slide --}}
                @if ($small1Product)
                    <div class="side-game-card">
                         <a href="{{ route('products.show', $small1Product) }}">
                            <img src="{{ $small1Product->game->cover_url }}" alt="{{ $small1Product->name }}">
                        </a>
                    </div>
                @endif
                {{-- 5. Exibe o produto 'small_2' deste slide --}}
                @if ($small2Product)
                    <div class="side-game-card">
                         <a href="{{ route('products.show', $small2Product) }}">
                            <img src="{{ $small2Product->game->cover_url }}" alt="{{ $small2Product->name }}">
                        </a>
                    </div>
                @endif
            </div>

        </section>
    @endforeach {{-- Fim do loop de slides --}}

    {{-- Seu HTML para setas e paginação (dots) vai aqui --}}
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
        
        <a href="#" class="game-card">
            <img src="{{ $product->game->cover_url }}" alt="{{ $product->name }}">
            <div class="game-info">
                <h3>{{$product->name}}</h3>
                <p class="game-platform">{{$product->platform->name}}</p>
                <div class="price-info">
                    <span class="discount-tag">-82%</span>
                    <span class="price-tag">R$ {{$product->current_price}}</span>
                </div>
            </div>
        </a>
@endforeach
    </div>
</section>


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
                <a href="index.html"><img src="/images/GettStore Branco s fundo.png" alt="GettStore Avatar Logo" class="footer-logo"></a>
                <p class="footer-legal">
                    GettStore Ltda. - CNPJ 00.000.000/0000-00<br>
                    Rua Lauro Müller, nº 116, sala 503 - Torre do Rio Sul - Botafogo - Rio de Janeiro, RJ – 22290-160
                </p>
            </div>
        </div>
    </footer>
    </body>
</html>