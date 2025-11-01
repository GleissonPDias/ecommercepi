<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Novo Jogo</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background-color: #f4f4f4; }
        form { max-width: 600px; margin: 0 auto; background: #fff; border: 1px solid #ccc; padding: 25px; border-radius: 8px; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: bold; }
        .form-group input,
        .form-group select,
        .form-group textarea { width: 100%; padding: 10px; box-sizing: border-box; border: 1px solid #ddd; border-radius: 5px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        button { background: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; }
        
        /* Mensagens de feedback */
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .error-message { color: #dc3545; font-size: 0.875em; margin-top: 5px; }
    </style>
</head>
<body>

    <form action="{{ route('admin.games.store') }}" method="POST">
        @csrf
        
        <h1>Adicionar Novo Jogo ao Catálogo</h1>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="form-group">
            <label for="title">Título do Jogo</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
            @error('title')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="developer_id">Desenvolvedor</label>
            <select id="developer_id" name="developer_id" required>
                <option value="">-- Selecione um Desenvolvedor --</option>
                @foreach ($developers as $developer)
                    <option value="{{ $developer->id }}" {{ old('developer_id') == $developer->id ? 'selected' : '' }}>
                        {{ $developer->name }}
                    </option>
                @endforeach
            </select>
            @error('developer_id')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="publisher_id">Editor (Publisher)</label>
            <select id="publisher_id" name="publisher_id" required>
                <option value="">-- Selecione um Editor --</option>
                @foreach ($publishers as $publisher)
                    <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                        {{ $publisher->name }}
                    </option>
                @endforeach
            </select>
            @error('publisher_id')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="cover_url">URL da Imagem de Capa</label>
            <input type="text" id="cover_url" name="cover_url" value="{{ old('cover_url') }}" placeholder="https://..." required>
            @error('cover_url')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label for="release_date">Data de Lançamento</label>
                <input type="date" id="release_date" name="release_date" value="{{ old('release_date') }}" required>
                @error('release_date')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="age_rating">Classificação Etária (Ex: 18+)</label>
                <input type="text" id="age_rating" name="age_rating" value="{{ old('age_rating') }}" required>
                @error('age_rating')
                    <small class="error-message">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="about">Sobre o Jogo (Descrição)</label>
            <textarea id="about" name="about" rows="5" required>{{ old('about') }}</textarea>
            @error('about')
                <small class="error-message">{{ $message }}</small>
            @enderror
        </div>
        
        <button type="submit">Salvar Jogo</button>
    </form>

</body>
</html>