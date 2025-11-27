@extends('layouts.app')

@section('title', $product->name)

@section('content')

    <main class="product-page">
        <div class="content-left">
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

            {{-- =============================================== --}}
            {{-- ============ SEÇÃO DE DLCS RELACIONADAS ========= --}}
            {{-- =============================================== --}}
            @if ($dlcProducts->isNotEmpty())
                <div class="related-products">
                    <h3>Produtos Relacionados</h3>

                    <div class="related-products-list">
                        @foreach ($dlcProducts as $dlc)
                            @if ($dlc && $dlc->game)
                                <div class="dlc-card">
                                    
                                    {{-- LADO ESQUERDO: Link, Imagem e Informações --}}
                                    <a href="{{ route('products.show', $dlc) }}" class="dlc-link-area">
                                        <div class="dlc-image-wrapper">
                                            <img src="{{ Storage::url($dlc->game->cover_url) }}" alt="{{ $dlc->name }}" />
                                        </div>
                                        <div class="dlc-info">
                                            <h4>{{ $dlc->name }}</h4>
                                            <span class="platform-badge"> {{ $dlc->platform->name }}</span>
                                        </div>
                                    </a>

                                    {{-- LADO DIREITO: Preço e Botões --}}
                                    <div class="dlc-actions-area">
                                        
                                        {{-- Preço --}}
                                        <div class="dlc-pricing">
                                            {{-- Exemplo de desconto estático, pode adicionar lógica se tiver --}}
                                             
                                            <span class="final-price">R$ {{ number_format($dlc->current_price, 2, ',', '.') }}</span>
                                        </div>

                                        {{-- Botão Adicionar ao Carrinho --}}
                                        <form method="POST" action="{{ route('cart.store', $dlc) }}">
                                            @csrf
                                            <button type="submit" class="btn-dlc-buy">
                                                <i class="fas fa-shopping-cart"></i>
                                            </button>
                                        </form>

                                        {{-- Botão Favorito --}}
                                        <form method="POST" action="{{ route('favorites.toggle', $dlc) }}">
                                            @csrf
                                            <button type="submit" class="btn-dlc-fav">
                                                @if(auth()->user() && auth()->user()->favorites->contains($dlc))
                                                    <i class="fas fa-heart"></i>
                                                @else
                                                    <i class="far fa-heart"></i>
                                                @endif
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            @endif
                        @endforeach
                    </div>
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
                <div class="base-game-container">
                    {{-- Texto de Aviso --}}
                    <p class="base-game-alert">
                        <i class="fas fa-exclamation-circle"></i> Este conteúdo requer o jogo base:
                    </p>

                    {{-- Card Horizontal (Reutilizando o estilo .dlc-card) --}}
                    <div class="dlc-card">
                        
                        {{-- LADO ESQUERDO: Link, Imagem e Texto --}}
                        <a href="{{ route('products.show', $baseGameProduct) }}" class="dlc-link-area">
                            <div class="dlc-image-wrapper">
                                <img src="{{ Storage::url($baseGameProduct->game->cover_url) }}" alt="{{ $baseGameProduct->name }}" />
                            </div>
                            <div class="dlc-info">
                                <h4>{{ $baseGameProduct->name }}</h4>
                                <span class="platform-badge">
                                    {{ $baseGameProduct->platform->name }}
                                </span>
                            </div>
                        </a>

                        {{-- LADO DIREITO: Preço e Botões --}}
                        <div class="dlc-actions-area">
                            
                            {{-- Preço --}}
                            <div class="dlc-pricing">
                                <span class="final-price">
                                    R$ {{ number_format($baseGameProduct->current_price, 2, ',', '.') }}
                                </span>
                            </div>

                            {{-- Botão Adicionar ao Carrinho --}}
                            <form method="POST" action="{{ route('cart.store', $baseGameProduct) }}">
                                @csrf
                                <button type="submit" class="btn-dlc-buy">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </form>

                            {{-- Botão Favorito --}}
                            <form method="POST" action="{{ route('favorites.toggle', $baseGameProduct) }}">
                                @csrf
                                <button type="submit" class="btn-dlc-fav">
                                    @if(auth()->user() && auth()->user()->favorites->contains($baseGameProduct))
                                        <i class="fas fa-heart"></i>
                                    @else
                                        <i class="far fa-heart"></i>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="content-right">
            <div class="sidebar-card">
                <img src="{{ Storage::url($product->game->cover_url) }}" alt="{{ $product->name }}" />
                <h1 class="game-title">{{ $product->name }}</h1>
                <p class="game-genre">
                    @foreach($product->game->categories as $category)
                        {{ $category->name }}{{ !$loop->last ? ',' : '' }}
                    @endforeach
                </p>

                {{-- CORRIGIDO: Ícone de Favorito (agora é um formulário) --}}
                <form method="POST" action="{{ route('favorites.toggle', $product) }}" class="form-favorite-toggle">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1.8rem; text-shadow: 0 0 5px black;">
                        @if(auth()->user() && auth()->user()->favorites->contains($product))
                            <i class="fas fa-heart" style="color: red;"></i>
                        @else
                            <i class="far fa-heart"></i>
                        @endif
                    </button>
                </form>

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