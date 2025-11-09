<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    {{-- O título agora é dinâmico. A página filha vai definir o @yield('title') --}}
    <title>GettStore - @yield('title', 'Sua Loja de Jogos')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
    
    {{-- (Pode adicionar um @yield('styles') aqui se páginas específicas precisarem de CSS extra) --}}
</head>
<body>
    {{-- =============================================== --}}
    {{-- SEU HEADER (PARTE DO MOLDE) --}}
    {{-- =============================================== --}}
    <header class="main-header">
        <div class="header-left">
            <button class="btn-menu" type="button" aria-label="Abrir menu">
                <i class="fas fa-bars"></i>
            </button>
            <a href="{{ route('home') }}"
                ><img src="{{ asset('images/logo.svg') }}" alt="logo" class="logo"
            /></a>
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
                <a href="{{ route('cart.index') }}"
                    ><i class="fas fa-shopping-cart"></i
                ></a>
                {{-- (Este contador pode ser preenchido dinamicamente no futuro) --}}
                <span class="cart-count">{{$cartItems->count()}}</span> 
            </div>
            
            @auth {{-- Mostra apenas se o usuário estiver logado --}}
                <a href="{{ route('profile.edit') }}"><i class="fas fa-user"></i></a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline-flex; align-items: center; margin-left: 10px;">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       title="Sair"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt" style="color: white; font-size: 1.2rem;"></i>
                    </a>
                </form>
            @else {{-- Mostra "Entrar" se for visitante --}}
                 <a href="{{ route('login') }}" style="color: white; text-decoration: none; margin-left: 10px; font-weight: 500;">Entrar</a>
            @endauth
        </div>
    </header>

    {{-- =============================================== --}}
    {{-- SUA SIDEBAR (PARTE DO MOLDE) --}}
    {{-- =============================================== --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <button class="btn-close" type="button" aria-label="Fechar menu">
                <i class="fas fa-times"></i>
            </button>
        </div>
        {{-- (Seu menu de links da sidebar) --}}
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

    {{-- =============================================== --}}
    {{-- O "BURACO" ONDE O CONTEÚDO DA PÁGINA ENTRARÁ --}}
    {{-- =============================================== --}}
    <main>
        @yield('content')
    </main>
    
    {{-- =============================================== --}}
    {{-- SEU FOOTER (PARTE DO MOLDE) --}}
    {{-- =============================================== --}}
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
            <hr class="footer-divider" />
            <div class="footer-bottom">
                <a href="{{ route('home') }}"
                    ><img
                        src="{{ asset('images/GettStore Branco s fundo.png') }}"
                        alt="GettStore Avatar Logo"
                        class="footer-logo"
                /></a>
                <p class="footer-legal">
                    GettStore Ltda. – CNPJ 00.000.000/0000-00<br />
                    Rua Lauro Müller, nº 116, sala 503 - Torre do Rio Sul - Botafogo -
                    Rio de Janeiro, RJ – 22290-160
                </p>
            </div>
        </div>
    </footer>
</body>
</html>