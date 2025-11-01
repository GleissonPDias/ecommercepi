<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- O título vem do produto que o Controller enviou --}}
    <title>{{ $product->name }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="font-family: sans-serif; padding: 20px;">

    {{-- Link para voltar --}}
    <a href="{{ route('home') }}"><< Voltar para a Loja</a>

    <main style="margin-top: 20px;">
        
        {{-- A capa vem do relacionamento com 'game' --}}
        <img src="{{ $product->game->cover_url }}" alt="{{ $product->name }}" style="max-width: 400px;">
        
        {{-- O nome vem do 'product' --}}
        <h1>{{ $product->name }}</h1>
        
        {{-- O preço vem do 'product' --}}
        <h2 style="color: green;">R$ {{ $product->current_price }}</h2>

        {{-- A plataforma vem do relacionamento com 'platform' --}}
        <p><strong>Plataforma:</strong> {{ $product->platform->name }}</p>

        {{-- A descrição vem do relacionamento com 'game' --}}
        <p><strong>Sobre o jogo:</strong><br> {{ $product->game->about }}</p>

    </main>
</body>
</html>