<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Novo Jogo (Tudo-em-Um)</title>
    
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            padding: 30px;
            background-color: #f6f8fa;
            color: #24292e;
        }
        form {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e1e4e8;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        h1 {
            text-align: center;
            border-bottom: 1px solid #e1e4e8;
            padding-bottom: 15px;
            margin-top: 0;
        }

        /* Grupos de Formulário */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label,
        fieldset legend {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="file"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #d1d5da;
            border-radius: 6px;
            font-size: 14px;
        }
        .form-group textarea {
            min-height: 100px;
        }
        
        /* Layout em Grade */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Grupos Refatorados (com "OU Crie Novo") */
        fieldset {
            border: 1px solid #d1d5da;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        fieldset legend {
            padding: 0 10px;
            margin-left: 5px;
        }
        fieldset .label-or {
            font-weight: 600;
            font-size: 13px;
            text-align: center;
            margin: 15px 0 8px 0;
            color: #586069;
        }

        /* Botão */
        button {
            background-color: #2ea44f;
            color: white;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
        }
        button:hover {
            background-color: #2c974b;
        }
        
        /* Mensagens de feedback */
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .error-message {
            color: #d73a49;
            font-size: 0.875em;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <h1>Adicionar Novo Jogo ao Catálogo</h1>

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

        <div>
            <label for="base_game_id">Este jogo é uma DLC de: </label>
            <select name="base_game_id" id="base_game_id">
                <option value="">-- Este é um Jogo-Base (Não é DLC) --</option>
                @foreach ($baseGames as $game)
                    <option value="{{$game->id}}">{{$game->title}}</option>
                    
                @endforeach
            </select>
        </div>



        <fieldset>
            <legend>Desenvolvedor</legend>
            <div class="form-group">
                <label for="developer_id">Selecionar Existente</label>
                <select id="developer_id" name="developer_id">
                    <option value="">-- Selecione um Desenvolvedor --</option>
                    @foreach ($developers as $developer)
                        <option value="{{ $developer->id }}" {{ old('developer_id') == $developer->id ? 'selected' : '' }}>
                            {{ $developer->name }}
                        </option>
                    @endforeach
                </select>
                @error('developer_id') <small class="error-message">{{ $message }}</small> @enderror
            </div>
            
            <label for="new_developer" class="label-or">...OU Crie um Novo</label>
            <input type="text" id="new_developer" name="new_developer" value="{{ old('new_developer') }}" placeholder="Ex: FromSoftware">
            @error('new_developer') <small class="error-message">{{ $message }}</small> @enderror
        </fieldset>

        <fieldset>
            <legend>Editor (Publisher)</legend>
            <div class="form-group">
                <label for="publisher_id">Selecionar Existente</label>
                <select id="publisher_id" name="publisher_id">
                    <option value="">-- Selecione um Editor --</option>
                    @foreach ($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                    @endforeach
                </select>
                @error('publisher_id') <small class="error-message">{{ $message }}</small> @enderror
            </div>
            
            <label for="new_publisher" class="label-or">...OU Crie um Novo</label>
            <input type="text" id="new_publisher" name="new_publisher" value="{{ old('new_publisher') }}" placeholder="Ex: Bandai Namco">
            @error('new_publisher') <small class="error-message">{{ $message }}</small> @enderror
        </fieldset>
        
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


            <fieldset>
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

        <fieldset>
            <legend>Categorias</legend>
            <div class="form-group">
                <label for="categories">Selecionar Existentes (Segure Ctrl para várias)</label>
                <select name="categories[]" id="categories" multiple size="5">
                    @foreach ($categories as $category) 
                        {{-- Lógica 'old' para multi-select --}}
                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('categories') <small class="error-message">{{ $message }}</small> @enderror
            </div>
            
            <label for="new_category" class="label-or">...OU Crie uma Nova Categoria</label>
            <input type="text" id="new_category" name="new_category" value="{{ old('new_category') }}" placeholder="Ex: Soulslike">
            @error('new_category') <small class="error-message">{{ $message }}</small> @enderror
        </fieldset>
        
        <div class="form-group">
            <label for="images">Imagens da Galeria (Opcional, selecione várias)</label>
            <input type="file" id="images" name="images[]" multiple>
            @error('images') <small class="error-message">{{ $message }}</small> @enderror
            @error('images.*') <small class="error-message">{{ $message }}</small> @enderror
        </div>
        
        <button type="submit">Salvar Jogo</button>
    </form>

</body>
</html>