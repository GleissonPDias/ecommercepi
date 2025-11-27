@extends('layouts.admin')

@section('title', 'Criar Novo Jogo')

@section('content')
<body>

    <form class="form-carrossel" action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        
        @if (session('error'))
            <div class="error-message" style="background: #f8d7da; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
                <strong>Erro:</strong> {{ session('error') }}
            </div>
        @endif


        <div class="form-group">
            <label for="title">Nome do Jogo/DLC</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required>
            @error('title') <small class="error-message">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="base_game_id">Este jogo é uma DLC de: </label>
            <select name="base_game_id" id="base_game_id">
                <option value="">-- Este é um Jogo-Base (Não é DLC) --</option>
                @foreach ($baseGames as $game)
                    <option value="{{$game->id}}">{{$game->title}}</option>
                    
                @endforeach
            </select>
        </div>
        <div class="grid-2">
            <fieldset class="form-field">
                <legend>Desenvolvedor</legend>
                <div class="form-group">
                    <label for="developer_id">Selecionar existente</label>
                    <select id="developer_id" name="developer_id">
                        <option value="">-- Selecione um Desenvolvedor --</option>
                        @foreach ($developers as $developer)
                            <option value="{{ $developer->id }}" {{ old('developer_id') == $developer->id ? 'selected' : '' }}>
                                {{ $developer->name }}
                            </option>
                        @endforeach
                    </select>
                        @error('developer_id') <small class="error-message">{{ $message }}</small> @enderror
                            <label for="new_developer" class="label-or">Ou crie um novo</label>
                            <input type="text" id="new_developer" name="new_developer" value="{{ old('new_developer') }}" placeholder="Ex: FromSoftware">
                        @error('new_developer') <small class="error-message">{{ $message }}</small> @enderror
                </div>
            </fieldset>

            <fieldset class="form-field">
                <legend>Editor (Publisher)</legend>
                <div class="form-group">
                    <label for="publisher_id">Selecionar existente</label>
                    <select id="publisher_id" name="publisher_id">
                        <option value="">-- Selecione um Editor --</option>
                        @foreach ($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                {{ $publisher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('publisher_id') <small class="error-message">{{ $message }}</small> @enderror
                        <label for="new_publisher" class="label-or">Ou crie um novo</label>
                        <input type="text" id="new_publisher" name="new_publisher" value="{{ old('new_publisher') }}" placeholder="Ex: Bandai Namco">
                    @error('new_publisher') <small class="error-message">{{ $message }}</small> @enderror
                </div>
            </fieldset>
        </div>
        <div class="form-group">
            <label for="cover_image">Imagem de Capa Principal (Arquivo)</label>
            <input type="file" id="cover_image" name="cover_image" required>
            @error('cover_image') <small class="error-message">{{ $message }}</small> @enderror
        </div>
        
        <div class="grid-2">
            <div class="form-group">
                <label for="release_date">Data de Lançamento</label>
                <input type="date" id="release_date" name="release_date" value="{{ old('release_date') }}" required>
                @error('release_date') <small class="error-message">{{ $message }}</small> @enderror
            </div>
            
            <div class="form-group">
                <label for="age_rating">Classificação Etária (Ex: 18+)</label>
                <input type="text" id="age_rating" name="age_rating" value="{{ old('age_rating') }}" required>
                @error('age_rating') <small class="error-message">{{ $message }}</small> @enderror
            </div>
        </div>

        <fieldset class="form-field">
            <legend>Modos de Jogo</legend>
            <div class="form-group">
                <label for="game_mode">Selecionar Existentes (Segure Ctrl para várias)</label>
                <select name="game_modes[]" id="game_modes" multiple size="4">
                    @foreach ($gameModes as $mode) 
                        {{-- Lógica 'old' para multi-select --}}
                        <option value="{{ $mode->id }}" {{ in_array($mode->id, old('game_modes', [])) ? 'selected' : '' }}>
                            {{ $mode->name }}
                        </option>
                    @endforeach
                </select>
                @error('gamemodes') <small class="error-message">{{ $message }}</small> @enderror
            </div>
        </fieldset>

        <div class="form-group">
            <label for="about">Sobre o Jogo (Descrição)</label>
            <textarea id="about" name="about" rows="5" required>{{ old('about') }}</textarea>
            @error('about') <small class="error-message">{{ $message }}</small> @enderror
        </div>

        <fieldset class="form-field">
            <legend>Categorias</legend>
            <div class="form-group">
                <label for="categories">Selecionar existentes (Segure Ctrl para várias)</label>
                <select name="categories[]" id="categories" multiple size="5">
                    @foreach ($categories as $category) 
                        {{-- Lógica 'old' para multi-select --}}
                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('categories') <small class="error-message">{{ $message }}</small> @enderror
                    <label for="new_category" class="label-or">Ou crie uma nova categoria</label>
                    <input type="text" id="new_category" name="new_category" value="{{ old('new_category') }}" placeholder="Ex: Soulslike">
                @error('new_category') <small class="error-message">{{ $message }}</small> @enderror
            </div>
        </fieldset>
        
        <div class="form-group">
            <label for="images">Imagens da Galeria (Opcional, selecione várias)</label>
            <input type="file" id="images" name="images[]" multiple>
            @error('images') <small class="error-message">{{ $message }}</small> @enderror
            @error('images.*') <small class="error-message">{{ $message }}</small> @enderror
        </div>
        
        <button type="submit" class="btn-create">Salvar Jogo</button>
    </form>

</body>
@endsection