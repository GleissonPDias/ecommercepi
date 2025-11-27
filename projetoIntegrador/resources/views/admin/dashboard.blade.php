@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

{{-- 1. Diz ao Blade para "vestir" o layout de admin --}}
@extends('layouts.admin')

{{-- 2. Define o título da página (para o @yield('title')) --}}
@section('title', 'Painel Principal')

{{-- 3. Define o conteúdo principal (para o @yield('content')) --}}
@section('content')

    <h2>Bem-vindo ao Painel de Controle, {{ auth()->user()->name }}!</h2>
    <p>Aqui você pode gerenciar todos os aspectos da sua loja.</p>

    <div class="dashboard-stats">
        {{-- Você pode adicionar estatísticas aqui no futuro --}}
        <div class="stat-card">
            <h3>Jogos Cadastrados</h3>
            <p>15</p>
            <h3>Vendas Hoje</h3>
            <p>R$ 0,00</p>
        </div>
    </div>

@endsection