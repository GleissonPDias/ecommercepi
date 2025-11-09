<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Admin - GettStore</title>
    
    {{-- Carrega o seu CSS de admin. Você precisará criar este arquivo --}}
    @vite(['resources/css/admin.css'])
    
    {{-- Font Awesome para ícones --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body class="admin-body">

    {{-- 1. BARRA LATERAL DE NAVEGAÇÃO --}}
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/GettStore.png') }}" alt="Logo" class="admin-logo">
        </div>
        
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-link">
                <i class="fas fa-users-cog"></i> Gerenciar Usuários
            </a>

            <a href="{{ route('admin.games.index') }}" class="nav-link">
                <i class="fas fa-gamepad"></i> Gerenciar Jogos
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i class="fas fa-box-open"></i> Gerenciar Produtos
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link">
                <i class="fas fa-tags"></i> Categorias
            </a>
            <a href="{{ route('admin.developers.index') }}" class="nav-link">
                <i class="fas fa-code"></i> Developers
            </a>
            <a href="{{ route('admin.publishers.index') }}" class="nav-link">
                <i class="fas fa-building"></i> Publishers
            </a>
            <a href="{{ route('admin.platforms.index') }}" class="nav-link">
                <i class="fab fa-windows"></i> Plataformas
            </a>
            <a href="{{ route('admin.carousel') }}" class="nav-link">
                <i class="fas fa-images"></i> Carrossel
            </a>
            {{-- Adicione o link para 'users.index' se você já criou a rota --}}
            {{-- <a href="{{ route('admin.users.index') }}" class="nav-link">
                <i class="fas fa-users-cog"></i> Gerenciar Usuários
            </a> --}}
        </nav>
        
        <div class="sidebar-footer">
            <a href="{{ route('home') }}" class="nav-link" target="_blank">
                <i class="fas fa-eye"></i> Ver Loja
            </a>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" class="nav-link" 
                   onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </form>
        </div>
    </aside>

    {{-- 2. CONTEÚDO PRINCIPAL (HEADER + PÁGINA) --}}
    <div class="admin-main-content">
        
        <header class="admin-header">
            <h2>@yield('title', 'Dashboard')</h2> {{-- Título da Página --}}
            <div class="admin-user-info">
                <span>Olá, {{ auth()->user()->name }}</span>
                <i class="fas fa-user-shield"></i>
            </div>
        </header>

        {{-- 3. O PONTO ONDE O CONTEÚDO É INJETADO --}}
        <main class="admin-content">
            @yield('content')
        </main>
        
    </div>



    <a href="{{ route('admin.users.index') }}" class="nav-link">
        <i class="fas fa-users-cog"></i> Gerenciar Usuários
    </a>



</body>
</html>