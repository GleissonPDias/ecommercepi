@extends('layouts.admin')

@section('title', 'Criar Novo Produto')

@section('content')
<body>

    {{-- Ação correta, apontando para a rota de admin --}}
    <form class="form-carrossel" action="{{ route('admin.products.store') }}" method="POST">
        @csrf
        
        <div class="grid-2">
            <div class="form-group">
                <label for="game_id">Este produto é para qual Jogo/DLC?</label>
                <select name="game_id" id="game_id" required>
                <option value="">-- Selecione um Jogo --</option>
                @foreach ($games as $game)
                    <option value="{{ $game->id }}">{{ $game->title }}</option>
                @endforeach
                </select>
            </div>

            {{-- Dropdown para Plataforma --}}
            <div class="form-group">
                <label for="platform_id">Plataforma</label>
                <select id="platform_id" name="platform_id" required>
                    <option value="">-- Selecione uma Plataforma --</option>
                    {{-- Esta variável $platforms deve vir do seu ProductController@create --}}
                    @foreach ($platforms as $platform)
                        <option value="{{ $platform->id }}" {{ old('platform_id') == $platform->id ? 'selected' : '' }}>
                            {{ $platform->name }}
                        </option>
                    @endforeach
                </select>
                @error('platform_id') <small>{{ $message }}</small> @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label for="name">Nome do Produto (Ex: Edição Deluxe - Steam)</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <small>{{ $message }}</small> @enderror
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label for="default_price">Preço Padrão (Ex: 299.99)</label>
                <input type="number" id="default_price" name="default_price" step="0.01" value="{{ old('default_price') }}" required>
                @error('default_price') <small>{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="current_price">Preço Atual (Promocional)</label>
                <input type="number" id="current_price" name="current_price" step="0.01" value="{{ old('current_price') }}" required>
                @error('current_price') <small>{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- Checkbox para o Carrossel Secundário (Este está correto) --}}
        <div class="form-group-checkbox">
            <input type="checkbox" id="is_featured_secondary" name="is_featured_secondary" value="1" {{ old('is_featured_secondary') ? 'checked' : '' }}>
            <label for="is_featured_secondary">Destaque no Carrossel Secundário?</label>
        </div>

        <div class="form-group-checkbox">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active">Ativo (Visível na loja)?</label>
        </div>
        
        <br>
        <button class="btn-create" type="submit">Criar Produto</button>
    </form>

</body>
@endsection