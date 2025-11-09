

   
<style>
        body { font-family: sans-serif; padding: 20px; }
        form { max-width: 600px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; border-radius: 8px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group select { width: 100%; padding: 8px; box-sizing: border-box; }
        .form-group-checkbox { display: flex; align-items: center; gap: 10px; }
        button { background: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px; }
        small { color: red; }
</style>



    {{-- Ação correta, apontando para a rota de admin --}}
<form action="{{ route('admin.products.update', $product) }}" method="POST">
    @method('PUT')
    @csrf
    <h1>Editar o produto: {{ $product->name }}</h1>
    
    <div class="form-group">
        <label for="game_id">Este produto é para qual Jogo/DLC?</label>
        <select name="game_id" id="game_id" required>
        <option value="">-- Selecione um Jogo --</option>
        @foreach ($games as $game)
            {{-- CORRIGIDO: Adiciona 'selected' se o ID bater --}}
            <option value="{{ $game->id }}"
                @if(old('game_id', $product->game_id) == $game->id) selected @endif
            >
                {{ $game->title }}
            </option>
        @endforeach
        </select>
        @error('game_id') <small>{{ $message }}</small> @enderror
    </div>

    <div class="form-group">
        <label for="platform_id">Plataforma</label>
        <select id="platform_id" name="platform_id" required>
            <option value="">-- Selecione uma Plataforma --</option>
            @foreach ($platforms as $platform)
                {{-- CORRIGIDO: Adiciona 'selected' se o ID bater --}}
                <option value="{{ $platform->id }}" 
                    @if(old('platform_id', $product->platform_id) == $platform->id) selected @endif
                >
                    {{ $platform->name }}
                </option>
            @endforeach
        </select>
        @error('platform_id') <small>{{ $message }}</small> @enderror
    </div>
    
    <div class="form-group">
        <label for="name">Nome do Produto (Ex: Edição Deluxe - Steam)</label>
        {{-- CORRIGIDO: Adiciona o valor do $product no 'value' --}}
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        @error('name') <small>{{ $message }}</small> @enderror
    </div>
    
    <div class="form-group">
        <label for="default_price">Preço Padrão (Ex: 299.99)</label>
        {{-- CORRIGIDO: Adiciona o valor do $product no 'value' --}}
        <input type="number" id="default_price" name="default_price" step="0.01" value="{{ old('default_price', $product->default_price) }}" required>
        @error('default_price') <small>{{ $message }}</small> @enderror
    </div>

    <div class="form-group">
        <label for="current_price">Preço Atual (Promocional)</label>
        {{-- CORRIGIDO: Adiciona o valor do $product no 'value' --}}
        <input type="number" id="current_price" name="current_price" step="0.01" value="{{ old('current_price', $product->current_price) }}" required>
        @error('current_price') <small>{{ $message }}</small> @enderror
    </div>

    {{-- CORRIGIDO: Lógica 'checked' para formulários de edição --}}
    <div class="form-group-checkbox">
        <input type="checkbox" id="is_featured_secondary" name="is_featured_secondary" value="1" 
            @if(session()->hasOldInput())
                 {{ old('is_featured_secondary') ? 'checked' : '' }}
            @else
                 {{ $product->is_featured_secondary ? 'checked' : '' }}
            @endif
        >
        <label for="is_featured_secondary">Destaque no Carrossel Secundário?</label>
    </div>

    <div class="form-group-checkbox">
        <input type="checkbox" id="is_active" name="is_active" value="1" 
            @if(session()->hasOldInput())
                 {{ old('is_active') ? 'checked' : '' }}
            @else
                 {{ $product->is_active ? 'checked' : '' }}
            @endif
        >
        <label for="is_active">Ativo (Visível na loja)?</label>
    </div>
    
    <br>
    {{-- CORRIGIDO: Texto do botão --}}
    <button type="submit">Atualizar Produto</button>
</form>