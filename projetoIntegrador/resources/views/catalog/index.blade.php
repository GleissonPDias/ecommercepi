{{-- 1. "Veste" o seu layout mestre (o mesmo da home/carrinho) --}}
@extends('layouts.app')

{{-- 2. Define o título da página --}}
@section('title', 'Plataforma ' . $platform->name)

{{-- 3. Injeta o conteúdo --}}
@section('content')

{{-- Usamos a classe da 'store-page' para manter o estilo --}}
<main class="store-page" style="padding: 20px;">

    {{-- Título da página de resultados (CORRIGIDO) --}}
    <h2 style="text-align: center; font-size: 2rem; margin-bottom: 20px;">
        Jogos para {{ $platform->name }}
    </h2>

    {{-- Verifica se encontramos algum produto --}}
    @if ($products->isEmpty())
        <div style="text-align: center; padding: 50px;">
            <i class="fas fa-search" style="font-size: 3rem; color: #888;"></i>
            {{-- Mensagem de Vazio (CORRIGIDA) --}}
            <p style="font-size: 1.2rem; margin-top: 15px;">Nenhum produto encontrado para {{ $platform->name }}.</p>
        </div>
    @else
        {{-- Usa o mesmo grid da sua "popular-games" --}}
        <section class="popular-games">
            <div class="games-carousel"> 
                
                @foreach ($products as $product)
                    {{-- 
                      REUTILIZA O MESMO CARD DE PRODUTO DA SUA HOME
                      (com 'form-favorite-toggle' e 'form-cart-store')
                    --}}
                    <div class="game-card">
                        
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

        {{-- Mostra os links de paginação --}}
        <div class="pagination-links" style="margin-top: 30px;">
            {{-- 
              CORRIGIDO: Remove o 'appends' da pesquisa. 
              (O Laravel trata a paginação do {platform} automaticamente)
            --}}
            {{ $products->links() }}
        </div>
        
    @endif
</main>
@endsection