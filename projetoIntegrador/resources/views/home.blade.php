{{-- 1. "Veste" o molde mestre (que tem o header/footer/search corretos) --}}
@extends('layouts.app')

{{-- 2. Define o título da página --}}
@section('title', 'Loja')

{{-- 3. Injeta este conteúdo no "buraco" @yield('content') do molde --}}
@section('content')

{{-- Usamos a classe da 'store-page' que você já tinha --}}
<main class="store-page">

    {{-- ======================================================= --}}
    {{-- INÍCIO DA ESTRUTURA DE LAYOUT (Filtros + Conteúdo) --}}
    {{-- ======================================================= --}}
    {{-- (Usamos o mesmo layout da página de pesquisa) --}}
    <div class="search-layout-container" style="padding: 20px;">

        {{-- 1. BARRA DE FILTROS (ESQUERDA) --}}
        <aside class="filter-sidebar">
            <h3>Filtrar</h3>
            
            {{-- 
              Este formulário envia o utilizador para a PÁGINA DE PESQUISA,
              já com os filtros aplicados.
            --}}
            <form action="{{ route('search.index') }}" method="GET">
                
                {{-- 
                  O campo 'query' está escondido e pesquisa por "*".
                  Isto é para que o SearchController procure "tudo"
                  mas com os seus filtros aplicados.
                --}}
                <input type="hidden" name="query" value="*">

                {{-- Filtro de Ordem --}}
                <div class="filter-group">
                    <label for="sort_by">Ordernar por:</label>
                    <select name="sort_by" id="sort_by">
                        <option value="default">Relevância</option>
                        <option value="price_asc">Preço: Menor para Maior</option>
                        <option value="price_desc">Preço: Maior para Menor</option>
                        <option value="name_asc">Nome: A-Z</option>
                        <option value="name_desc">Nome: Z-A</option>
                    </select>
                </div>

                {{-- Filtro de Categoria (AGORA DINÂMICO) --}}
                <div class="filter-group">
                    <label for="category_id">Categoria:</label>
                    <select name="category_id" id="category_id">
                        <option value="">Todas as Categorias</option>
                        {{-- O $categories vem do HomeController --}}
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Filtro de Preço --}}
                <div class="filter-group">
                    <label for="max_price">Preço Máximo (R$):</label>
                    <input type="number" name="max_price" id="max_price" 
                           placeholder="Ex: 50" min="1" step="1">
                </div>

                <button type="submit" class="btn-filter">Filtrar</button>
            </form>
        </aside>

        {{-- 2. CONTEÚDO DA HOME (CARROSSÉIS) (DIREITA) --}}
        <div class="search-results-content">
        
            {{-- O SEU CARROSSEL PRINCIPAL (Sem alterações) --}}
            <div class="featured-carousel-container">
                @foreach ($carouselSlides as $slide)
                    @php
                        $largeProduct = $slide->products->firstWhere('pivot.slot', 'large');
                        $small1Product = $slide->products->firstWhere('pivot.slot', 'small_1');
                        $small2Product = $slide->products->firstWhere('pivot.slot', 'small_2');
                    @endphp
                    <section class="featured-games-slide {{ $loop->first ? 'active' : '' }}" style="position: relative;">
                        
                        {{-- Produto Grande --}}
                        <div class="featured-main" style="position: relative;">
                            @if ($largeProduct)
                                <a href="{{ route('products.show', $largeProduct) }}">
                                    <img src="{{ Storage::url($largeProduct->game->cover_url) }}" alt="{{ $largeProduct->name }}">
                                </a>
                                <div class="price-overlay">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span>R$ {{ $largeProduct->current_price }}</span>
                                </div>
                                <form method="POST" action="{{ route('favorites.toggle', $largeProduct) }}" class="form-favorite-toggle" style="position: absolute; top: 20px; right: 20px; z-index: 10;">
                                    @csrf
                                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.8rem; text-shadow: 0 0 5px black;">
                                        @if(auth()->user() && auth()->user()->favorites->contains($largeProduct))
                                            <i class="fas fa-heart"></i>
                                        @else
                                            <i class="far fa-heart"></i>
                                        @endif
                                    </button>
                                </form>
                            @else
                                <a href="#"><img src="{{ asset('images/placeholder-large.png') }}" alt="Produto destaque"></a>
                            @endif
                        </div>
                        
                        {{-- Produtos Pequenos (Lateral) --}}
                        <div class="featured-sidebar">
                            @if ($small1Product)
                                <div class="side-game-card" style="position: relative;">
                                    <a href="{{ route('products.show', $small1Product) }}">
                                        <img src="{{ Storage::url($small1Product->game->cover_url) }}" alt="{{ $small1Product->name }}">
                                    </a>
                                    <form method="POST" action="{{ route('favorites.toggle', $small1Product) }}" class="form-favorite-toggle" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                        @csrf
                                        <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.5rem; text-shadow: 0 0 5px black;">
                                            @if(auth()->user() && auth()->user()->favorites->contains($small1Product))
                                                <i class="fas fa-heart"></i>
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
                                    <form method="POST" action="{{ route('favorites.toggle', $small2Product) }}" class="form-favorite-toggle" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                        @csrf
                                        <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.5rem; text-shadow: 0 0 5px black;">
                                            @if(auth()->user() && auth()->user()->favorites->contains($small2Product))
                                                <i class="fas fa-heart"></i>
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

            {{-- A SUA SECÇÃO "MAIS POPULARES" (Sem alterações) --}}
            <section class="popular-games">
                <h2>Mais Populares</h2>
                <div class="games-carousel"> 
                @foreach ($products as $product)
                    <div class="game-card">
                        <a href="{{ route('products.show', $product) }}" class="game-card-link">
                            <img src="{{ Storage::url($product->game->cover_url) }}" alt="{{ $product->name }}">
                            <div class="game-info">
                                <h3>{{$product->name}}</h3>
                                <p class="game-platform">{{$product->platform->name}}</p>
                                @if($product->current_price < $product->default_price )
                                <div class="item-price">
                                    <span class="old-price" style="color: red;">R$ {{ number_format($product->default_price, 2, ',', '.') }}</span>
                                    {{-- Cálculo de percentagem de desconto --}}
                                    <span class="discount-tag" style="color: red; background: #ffe0e0; padding: 2px 5px; border-radius: 4px; font-weight: bold;">
                                        -{{ number_format(100 - ($product->current_price / $product->default_price * 100), 0) }}%
                                    </span>
                                    <span class="new-price" style="color:green; display: block; font-size: 1.2rem;">R$ {{ number_format($product->current_price, 2, ',', '.') }}</span>
                                </div>
                                @else
                                <div class="item-price">
                                    <span class="new-price" style="color:green; font-size: 1.2rem;">R$ {{ number_format($product->current_price, 2, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                        </a>
                        
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
        
        </div> {{-- Fim do .search-results-content --}}
    </div> {{-- Fim do .search-layout-container --}}

</main>
@endsection