    @extends('layouts.admin')

    @section('title', 'Gerenciar Carrossel')

    @section('content')

<body>
    <div class="container2">

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form class="form-carrossel" action="{{ route('admin.carousel.store') }}" method="POST">
            @csrf
            <h2>Criar novo slide</h2>
            <div class="form-group">
                <label for="name">Nome do Slide (ex: "Promo de Verão")</label>
                <input type="text" id="name" name="name" required>
            </div>
            <button class="btn-create" type="submit">Criar slide</button>
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

                <button type="submit" class="btn-create">Atualizar Slide</button>
            </form>
        @endforeach
    </div>
</body>
@endsection