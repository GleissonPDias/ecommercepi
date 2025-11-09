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
        </div>
        <div class="stat-card">
            <h3>Vendas Hoje</h3>
            <p>R$ 0,00</p>
        </div>
    </div>

@endsection