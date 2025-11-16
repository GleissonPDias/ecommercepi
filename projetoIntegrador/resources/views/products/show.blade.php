{{-- 1. "Veste" o molde mestre (que tem o header/footer/search corretos) --}}
@extends('layouts.app')

{{-- 2. Define o título da página (para o @yield('title')) --}}
@section('title', $product->name)

{{-- 3. Injeta este conteúdo no "buraco" @yield('content') do molde --}}
@section('content')

    <main class="product-page">
        <div class="content-left" style="">
            <div class="main-banner">
                <img src="{{ Storage::url($product->game->cover_url) }}" alt="Capa do jogo" id="mainProductImage" />
                <button class="carousel-arrow prev" aria-label="Imagem anterior">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-arrow next" aria-label="Próxima imagem">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="thumbnail-gallery">
                <img src="{{ Storage::url($product->game->cover_url) }}" alt="Thumbnail 1" class="active-thumb" data-large-src="{{ Storage::url($product->game->cover_url) }}"/>
                @foreach ($product->game->images as $image)
                <img src="{{ Storage::url($image->image_url) }}"
                     alt="Thumbnail {{ $loop->iteration + 1 }}"
                     data-large-src="{{ Storage::url($image->image_url) }}" />
                @endforeach
            </div>
            <div class="system-requirements">
                <h2>Requisitos de Sistema</h2>
                @php
                    $minReq = $product->systemRequirements->firstWhere('type', 'minimum');
                    $recReq = $product->systemRequirements->firstWhere('type', 'recommended');
                @endphp
                <div class="requirements-columns">
                    <div class="req-column">
                        <h3>Mínimos</h3>
                        <ul>
                            <li><strong>SO:</strong> {{ $minReq->os ?? 'N/A' }}</li>
                            <li><strong>Processador:</strong> {{ $minReq->processor ?? 'N/A' }}</li>
                            <li><strong>Memória:</strong> {{ $minReq->memory ?? 'N/A' }}</li>
                            <li><strong>Placa Gráfica:</strong> {{ $minReq->graphics ?? 'N/A' }}</li>
                            <li><strong>Armazenamento:</strong> {{ $minReq->storage ?? 'N/A' }}</li>
                        </ul>
                    </div>
                    <div class="req-column">
                        <h3>Recomendados</h3>
                        <ul>
                            <li><strong>SO:</strong> {{ $recReq->os ?? 'N/A' }}</li>
                            <li><strong>Processador:</strong> {{ $recReq->processor ?? 'N/A' }}</li>
                            <li><strong>Memória:</strong> {{ $recReq->memory ?? 'N/A' }}</li>
                            <li><strong>Placa Gráfica:</strong> {{ $recReq->graphics ?? 'N/A' }}</li>
                            <li><strong>Armazenamento:</strong> {{ $recReq->storage ?? 'N/A' }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- ============ SEÇÃO DE DLCS RELACIONADAS ========= --}}
        {{-- =============================================== --}}
        @if ($dlcProducts->isNotEmpty())
            <div class="related-products">
                <h3>Produtos Relacionados</h3>

                <div class="related-products-list">
                    @foreach ($dlcProducts as $dlc)
                        @if ($dlc && $dlc->game)
                            {{-- CORRIGIDO: Adicionado position:relative para os botões --}}
                            <div class="dlc-card" style="position: relative;">
                                <a href="{{ route('products.show', $dlc) }}">
                                    <img src="{{ Storage::url($dlc->game->cover_url) }}"
                                         alt="Capa de {{ $dlc->name }}" />
                                    <h4>{{ $dlc->name }}</h4>
                                    <p class="dlc-price">
                                        R$ {{ number_format($dlc->current_price, 2, ',', '.') }}
                                    </p>
                                    {{-- CORRIGIDO: Mostra a plataforma da DLC --}}
                                    <p style="font-size: 0.9em; padding: 0 10px 10px; color: #ccc;">{{ $dlc->platform->name }}</p>
                                </a>
                                
                                {{-- CORRIGIDO: Botão de carrinho aponta para a DLC ($dlc) --}}
                                <form method="POST" action="{{ route('cart.store', $dlc) }}" class="form-cart-store">
                                    @csrf
                                    <button type="submit" class="btn-add-to-cart">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
                                {{-- CORRIGIDO: Botão de favorito aponta para a DLC ($dlc) --}}
                                <form method="POST" action="{{ route('favorites.toggle', $dlc) }}" class="form-favorite-toggle">
                                    @csrf
                                    <button type="submit">
                                        @if(auth()->user() && auth()->user()->favorites->contains($dlc))
                                            <i class="fas fa-heart"></i> {{-- Cheio --}}
                                        @else
                                            <i class="far fa-heart"></i> {{-- Vazio --}}
                                        @endif
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        {{-- =============================================== --}}
        {{-- ============ FIM DA SEÇÃO DE DLCS ============= --}}
        {{-- =============================================== --}}


        {{-- =============================================== --}}
        {{-- ============ MOSTRA O JOGO-BASE (SE FOR DLC) ===== --}}
        {{-- =============================================== --}}
        @if ($baseGameProduct)
            {{-- CORRIGIDO: Adicionado position:relative para os botões --}}
            <div class="base-game-link" style="position: relative;">
                <p>Requer o jogo-base:</p>
                <img src="{{Storage::url($baseGameProduct->game->cover_url)}}" alt="{{$baseGameProduct->name}}" class="item-image" style="width: 100%; height: 260px; object-fit: cover;"> 
                <a href="{{ route('products.show', $baseGameProduct) }}">
                    <strong>{{ $baseGameProduct->name }}</strong>
                </a>
                <p>{{$baseGameProduct->platform->name}}</p>
                <span class="new-price">R$ {{ number_format($baseGameProduct->current_price , 2, ',', '.') }}</span>
                
                {{-- CORRIGIDO: Botão de carrinho aponta para o JOGO BASE ($baseGameProduct) --}}
                <form method="POST" action="{{ route('cart.store', $baseGameProduct) }}" class="form-cart-store">
                    @csrf
                    <button type="submit" class="btn-add-to-cart">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </form>
                {{-- CORRIGIDO: Botão de favorito aponta para o JOGO BASE ($baseGameProduct) --}}
                <form method="POST" action="{{ route('favorites.toggle', $baseGameProduct) }}" class="form-favorite-toggle">
                    @csrf
                    <button type="submit">
                        @if(auth()->user() && auth()->user()->favorites->contains($baseGameProduct))
                            <i class="fas fa-heart"></i> {{-- Cheio --}}
                        @else
                            <i class="far fa-heart"></i> {{-- Vazio --}}
                        @endif
                    </button>
                </form>
            </div>
        @endif
        {{-- =============================================== --}}

        <div class="content-right">
            <div class="sidebar-card">
                <img src="{{ Storage::url($product->game->cover_url) }}" alt="{{ $product->name }}" />
                <h1 class="game-title">{{ $product->name }}</h1>
                <p class="game-genre">
                    @foreach($product->game->categories as $category)
                        {{ $category->name }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </p>
                <p class="game-price">R$ {{ number_format($product->current_price, 2, ',', '.') }}</p>
                <p class="activation-note">
                    Produto ativado através de <strong>chave de ativação</strong>
                </p>

                {{-- CORRIGIDO: Botão "Comprar" (leva ao carrinho) --}}
                <a href="{{ route('cart.index') }}"
                    ><button class="btn-buy">
                        <i class="fas fa-shopping-cart"></i> Comprar
                    </button></a
                >
                
                {{-- CORRIGIDO: Botão "Adicionar ao carrinho" (agora é um formulário) --}}
                <form method="POST" action="{{ route('cart.store', $product) }}" class="form-cart-store-main">
                    @csrf
                    {{-- Adiciona o seletor de quantidade que discutimos --}}
                    <button type="submit" class="btn-add-cart">Adicionar ao carrinho</button>
                </form>
                
                {{-- CORRIGIDO: Ícone de Favorito (agora é um formulário) --}}
                <form method="POST" action="{{ route('favorites.toggle', $product) }}" class="form-favorite-toggle" style="position: absolute; top: 15px; right: 15px;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.8rem; text-shadow: 0 0 5px black;">
                        @if(auth()->user() && auth()->user()->favorites->contains($product))
                            <i class="fas fa-heart" style="color: red;"></i>
                        @else
                            <i class="far fa-heart"></i>
                        @endif
                    </button>
                </form>

                <div class="game-info">
                    <h3>Sobre</h3>
                    <p>{{ $product->game->about }}</p>
                    <div class="info-grid">
                        <div>
                            <h3>Data de lançamento</h3>
                            <p>{{ $product->game->release_date->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <h3>Desenvolvedor</h3>
                            <p>{{ $product->game->developer->name }}</p>
                        </div>
                        <div>
                            <h3>Distribuidor</h3>
                            <p>{{ $product->game->publisher->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="game-meta">
                    {{-- CORRIGIDO: Modo de Jogo dinâmico --}}
<p>
        <strong>Modo de Jogo:</strong>
        @forelse ($product->game->gameModes as $mode)
            <span class="tag">{{ $mode->name }}</span>
        @empty
            <span style="color: #888;">Não informado</span>
        @endforelse
    </p>
                    <p>
                        <strong>Categoria:</strong>
                        @foreach($product->game->categories as $category)
                            <span class="tag">{{ $category->name }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
        </div>
    </main>
@endsection