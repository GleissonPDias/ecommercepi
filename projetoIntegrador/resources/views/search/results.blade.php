{{-- 1. "Veste" o seu layout mestre --}}
@extends('layouts.app')

{{-- 2. Define o título da página --}}
@section('title', 'Resultados para "' . $query . '"')

{{-- 3. Injeta o conteúdo --}}
@section('content')

{{-- (Adicionámos um ID e padding ao <main>) --}}
<main class="store-page" id="search-results-page" style="padding: 20px;">

    {{-- Título da página de resultados --}}
    <h2 style="text-align: center; font-size: 2rem; margin-bottom: 20px;">
        Resultados da Busca por: "{{ $query }}"
    </h2>

    {{-- ======================================================= --}}
    {{-- 👇 INÍCIO DO NOVO LAYOUT (Filtros + Resultados) 👇 --}}
    {{-- ======================================================= --}}
    <div class="search-layout-container">

        {{-- 1. BARRA DE FILTROS (ESQUERDA) --}}
        <aside class="filter-sidebar">
            <h3>Filtrar Resultados</h3>
            
            {{-- Este é o formulário de filtros --}}
            <form action="{{ route('search.index') }}" method="GET">
                
                {{-- 
                  INPUT ESCONDIDO: Essencial para "lembrar" o que
                  o utilizador pesquisou (ex: "cyberpunk")
                --}}
                <input type="hidden" name="query" value="{{ $query }}">

                {{-- Filtro de Ordem --}}
                <div class="filter-group">
                    <label for="sort_by">Ordernar por:</label>
                    <select name="sort_by" id="sort_by">
                        {{-- request('sort_by') "lembra" a seleção anterior --}}
                        <option value="default" @selected(request('sort_by') == 'default')>Relevância</option>
                        <option value="price_asc" @selected(request('sort_by') == 'price_asc')>Preço: Menor para Maior</option>
                        <option value="price_desc" @selected(request('sort_by') == 'price_desc')>Preço: Maior para Menor</option>
                        <option value="name_asc" @selected(request('sort_by') == 'name_asc')>Nome: A-Z</option>
                        <option value="name_desc" @selected(request('sort_by') == 'name_desc')>Nome: Z-A</option>
                    </select>
                </div>

                {{-- Filtro de Categoria --}}
                <div class="filter-group">
                    <label for="category_id">Categoria:</label>
                    <select name="category_id" id="category_id">
                        <option value="">Todas as Categorias</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Filtro de Preço --}}
                <div class="filter-group">
                    <label for="max_price">Preço Máximo (R$):</label>
                    <input type="number" name="max_price" id="max_price" 
                           placeholder="Ex: 50" min="1" step="1" 
                           value="{{ request('max_price') }}">
                </div>

                <button type="submit" class="btn-filter">Filtrar</button>
            </form>
        </aside>

        {{-- 2. GRELHA DE RESULTADOS (DIREITA) --}}
        <div class="search-results-content">
            @if ($products->isEmpty())
                <div style="text-align: center; padding: 50px;">
                    <i class="fas fa-search" style="font-size: 3rem; color: #888;"></i>
                    <p style="font-size: 1.2rem; margin-top: 15px;">Nenhum produto encontrado com estes filtros.</p>
                </div>
            @else
                <section class="popular-games">
                    {{-- (O seu @foreach e os .game-card estão aqui dentro) --}}
                    <div class="games-carousel"> 
                        @foreach ($products as $product)
                            <div class="game-card">
                                {{-- (O seu HTML de .game-card-link, .form-favorite-toggle, etc. entra aqui) --}}
                                <a href="{{ route('products.show', $product) }}" class="game-card-link">
                                    <img src="{{ Storage::url($product->game->cover_url) }}" alt="{{ $product->name }}">
                                    <div class="game-info">
                                        <h3>{{$product->name}}</h3>
                                        <p class="game-platform">{{$product->platform->name}}</p>
                                        <div class="item-price">
                                            <span class="old-price" style="color: red;">R$ {{ number_format($product->default_price, 2, ',', '.') }}</span>
                                            <span class="new-price" style="color:green;">R$ {{ number_format($product->current_price, 2, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('favorites.toggle', $product) }}" class="form-favorite-toggle"> ... </form>
                                <form method="POST" action="{{ route('cart.store', $product) }}" class="form-cart-store"> ... </form>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Paginação --}}
                <div class="pagination-links" style="margin-top: 30px;">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

