<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador do Carrossel</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        form { max-width: 800px; margin: 20px auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #007bff; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .slide-form { background-color: #f9f9f9; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <h1>Gerenciador do Carrossel Principal</h1>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.carousel.store') }}" method="POST">
        @csrf
        <h2>Criar Novo Slide</h2>
        <div class="form-group">
            <label for="name">Nome do Slide (ex: "Promo de Verão")</label>
            <input type="text" id="name" name="name" required>
        </div>
        <button type="submit">Criar Slide</button>
    </form>


    @foreach ($carouselSlides as $slide)
        
        {{-- Para cada slide, encontramos quais produtos estão em quais slots --}}
        @php
            $largeProdId = $slide->products->firstWhere('pivot.slot', 'large')?->id;
            $small1ProdId = $slide->products->firstWhere('pivot.slot', 'small_1')?->id;
            $small2ProdId = $slide->products->firstWhere('pivot.slot', 'small_2')?->id;
        @endphp

        <form action="{{ route('admin.carousel.update', $slide) }}" method="POST" class="slide-form">
            @csrf
            @method('PUT') {{-- Importante: Usamos PUT para atualização --}}

            <h2>Editando: {{ $slide->name }}</h2>

            {{-- Dropdown para o Slot "Large" --}}
            <div class="form-group">
                <label for="product_large_{{ $slide->id }}">Produto Destaque (Grande)</label>
                <select id="product_large_{{ $slide->id }}" name="product_large">
                    <option value="">-- Nenhum --</option>
                    @foreach ($allProducts as $product)
                        <option value="{{ $product->id }}" {{ $largeProdId == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (ID: {{ $product->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown para o Slot "Small 1" --}}
            <div class="form-group">
                <label for="product_small_1_{{ $slide->id }}">Produto Pequeno 1</label>
                <select id="product_small_1_{{ $slide->id }}" name="product_small_1">
                    <option value="">-- Nenhum --</option>
                    @foreach ($allProducts as $product)
                        <option value="{{ $product->id }}" {{ $small1ProdId == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (ID: {{ $product->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown para o Slot "Small 2" --}}
            <div class="form-group">
                <label for="product_small_2_{{ $slide->id }}">Produto Pequeno 2</label>
                <select id="product_small_2_{{ $slide->id }}" name="product_small_2">
                    <option value="">-- Nenhum --</option>
                    @foreach ($allProducts as $product)
                        <option value="{{ $product->id }}" {{ $small2ProdId == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} (ID: {{ $product->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit">Atualizar Slide</button>
        </form>
    @endforeach

</body>
</html>