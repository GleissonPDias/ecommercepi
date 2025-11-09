<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GettStore - PEAK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    />
    {{-- Apóstrofo solto removido daqui --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    />
</head>
<body>
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
            <div class="search-bar">
                <input type="text" placeholder="Buscar jogo ou palavra-chave" />
                <i class="fas fa-search"></i>
            </div>
        </div>
        <div class="header-right">
            <div class="cart-icon">
                <a href="{{route('cart.index')}}"><i class="fas fa-shopping-cart"></i></a>
                <span class="cart-count">{{$cartItems->count()}}</span> 
            </div>
            <i class="fas fa-heart active"></i>
            <a href="{{ route('profile.edit') }}"><i class="fas fa-user"></i></a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline-flex; align-items: center; margin-left: 10px;">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       title="Sair"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt" style="color: white; font-size: 1.2rem;"></i>
                    </a>
            </form>
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-header">
            <button class="btn-close" type="button" aria-label="Fechar menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <ul class="sidebar-links">
            <li>
                <a href="#"><i class="fas fa-gamepad"></i> Catálogo</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-tags"></i> Ofertas</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-gift"></i> Gift Card</a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#"><i class="fas fa-desktop"></i> PC</a>
            </li>
            <li>
                <a href="#"><i class="fab fa-xbox"></i> Xbox</a>
            </li>
            <li>
                <a href="#"><i class="fab fa-playstation"></i> Playstation</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-gamepad"></i> Switch</a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="#"><i class="fas fa-headset"></i> Suporte</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-ellipsis-h"></i> Mais</a>
            </li>
        </ul>
    </aside>

    <div class="overlay"></div>

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
        </div>

        {{-- =============================================== --}}
{{-- ============ SEÇÃO DE DLCS RELACIONADAS ========= --}}
{{-- =============================================== --}}
@if ($dlcProducts->isNotEmpty())
    <div class="related-products">
        <h3>Produtos Relacionados</h3>

        <div class="related-products-list">
            @foreach ($dlcProducts as $dlc)
                {{-- 
                    Verifica se a DLC (que é um Produto) tem um 'game' associado.
                    Isso é importante para pegar a imagem de capa e o nome.
                --}}
                @if ($dlc && $dlc->game)
                    <div class="dlc-card">
                        {{-- O link leva para a página de produto da própria DLC --}}
                        <a href="{{ route('products.show', $dlc) }}">
                            <img src="{{ Storage::url($dlc->game->cover_url) }}"
                                alt="Capa de {{ $dlc->name }}" />
                            <h4>{{ $dlc->name }}</h4>
                            <p class="dlc-price">
                                R$ {{ number_format($dlc->current_price, 2, ',', '.') }}
                            </p>
                            <p>{{$dlc->platform->name}}</p>
                        </a>
                                <form method="POST" action="{{ route('cart.store', $product) }}" class="form-cart-store">
        @csrf
            <button type="submit" class="btn-add-to-cart">
                    <i class="fas fa-shopping-cart"></i>
            </button>
        </form>
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
    <div class="base-game-link">
        <p>Requer o jogo-base:</p>
        <img src="{{Storage::url($baseGameProduct->game->cover_url)}}" alt="{{$baseGameProduct->name}}" class="item-image"> 
        <a href="{{ route('products.show', $baseGameProduct) }}">
            <strong>{{ $baseGameProduct->name }}</strong>
        </a>
        <p>{{$baseGameProduct->platform->name}}</p>
        <span class="new-price">R$ {{ number_format($baseGameProduct->current_price , 2, ',', '.') }}</span>
        <form method="POST" action="{{ route('cart.store', $product) }}" class="form-cart-store">
        @csrf
            <button type="submit" class="btn-add-to-cart">
                    <i class="fas fa-shopping-cart"></i>
            </button>
        </form>
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

                <a href="#"
                    ><button class="btn-buy">
                        <i class="fas fa-shopping-cart"></i> Comprar
                    </button></a
                >
                <button class="btn-add-cart">Adicionar ao carrinho</button>

                <div class="game-info">
                    <h3>Sobre</h3>
                    <p>
                        {{ $product->game->about }}
                    </p>

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
                    <p><strong>Modo de Jogo:</strong> Multijogador</p>
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

    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-columns">
                <div class="footer-column">
                    <h3>Seguir GettStore</h3>
                    <div class="social-icons">
                        <a href="#" aria-label="Instagram"
                            ><i class="fab fa-instagram"></i
                        ></a>
                        <a href="#" aria-label="Facebook"
                            ><i class="fab fa-facebook-f"></i
                        ></a>
                        <a href="#" aria-label="Twitter"
                            ><i class="fab fa-twitter"></i
                        ></a>
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
                        src="/images/GettStore Branco s fundo.png"
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