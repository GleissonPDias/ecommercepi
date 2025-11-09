{{-- 
  1. ADICIONADO "enctype" para permitir o upload de imagens 
  (sem isto, as suas 'images[]' e 'cover_image' vão falhar)
--}}
<form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <h1>Editar o jogo: {{ $game->title }} #{{ $game->id }} </h1>

    <div class="form-group">
        <label for="title">Nome do Jogo/DLC</label>
        {{-- 
          2. CORRIGIDO: Adicionado '$game->title' 
          (Faça isto para TODOS os inputs de texto/data)
        --}}
        <input type="text" id="title" name="title" value="{{ old('title', $game->title) }}" required>
        @error('title') <small class="error-message">{{ $message }}</small> @enderror
    </div>

    <div>
        <label for="base_game_id">Este jogo é uma DLC de: </label>
        <select name="base_game_id" id="base_game_id">
            <option value="">-- Este é um Jogo-Base (Não é DLC) --</option>
            
            {{-- (Renomeei a variável do loop para $baseGameItem para não dar conflito com o $game principal) --}}
            @foreach ($baseGames as $baseGameItem)
                <option value="{{ $baseGameItem->id }}"
                    {{-- 
                      3. CORRIGIDO: Adicionada a lógica 'selected' para o dropdown 
                    --}}
                    @if(old('base_game_id', $game->base_game_id) == $baseGameItem->id) selected @endif
                >
                    {{ $baseGameItem->title }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- (O seu fieldset vazio) --}}

    <fieldset>
        <legend>Desenvolvedor</legend>
        <div class="form-group">
            <label for="developer_id">Selecionar Existente</label>
            <select id="developer_id" name="developer_id">
                <option value="">-- Selecione um Desenvolvedor --</option>
                @foreach ($developers as $developer)
                    <option value="{{ $developer->id }}"
                        {{-- 4. CORRIGIDO: Lógica 'selected' para o developer --}}
                        @if(old('developer_id', $game->developer_id) == $developer->id) selected @endif
                    >
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
                    <option value="{{ $publisher->id }}"
                        {{-- 5. CORRIGIDO: Lógica 'selected' para o publisher --}}
                        @if(old('publisher_id', $game->publisher_id) == $publisher->id) selected @endif
                    >
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
        <br>
        <small>Deixe em branco para manter a capa atual:</small>
        <img src="{{ Storage::url($game->cover_url) }}" alt="{{ $game->title }}" width="100" style="margin-bottom: 10px;">
        
        {{-- 6. CORRIGIDO: Removido 'required' da imagem de capa --}}
        <input type="file" id="cover_image" name="cover_image">
        @error('cover_image') <small class="error-message">{{ $message }}</small> @enderror
    </div>
    
    <div class="grid-2">
        <div class="form-group">
            <label for="release_date">Data de Lançamento</label>
            {{-- CORRIGIDO: Preenchido o 'value' --}}
           <input type="date" id="release_date" name="release_date" value="{{ old('release_date', $game->release_date->format('Y-m-d')) }}" required>
            @error('release_date') <small class="error-message">{{ $message }}</small> @enderror
        </div>
        
        <div class="form-group">
            <label for="age_rating">Classificação Etária (Ex: 18+)</label>
            {{-- CORRIGIDO: Preenchido o 'value' --}}
            <input type="text" id="age_rating" name="age_rating" value="{{ old('age_rating', $game->age_rating) }}" required>
            @error('age_rating') <small class="error-message">{{ $message }}</small> @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="about">Sobre o Jogo (Descrição)</label>
        {{-- CORRIGIDO: Preenchido o 'textarea' --}}
        <textarea id="about" name="about" rows="5" required>{{ old('about', $game->about) }}</textarea>
        @error('about') <small class="error-message">{{ $message }}</small> @enderror
    </div>

    <fieldset>
        <legend>Categorias</legend>
        <div class="form-group">
            <label for="categories">Selecionar Existentes (Segure Ctrl para várias)</label>
            
            {{-- 
              7. CORRIGIDO: Lógica 'selected' para multi-select
              Primeiro, pegamos os IDs das categorias que o jogo JÁ TEM
            --}}
            @php
                $gameCategoryIds = $game->categories->pluck('id')->toArray();
            @endphp
            
            <select name="categories[]" id="categories" multiple size="5">
                @foreach ($categories as $category) 
                    <option value="{{ $category->id }}"
                        {{-- 
                          Verifica se a categoria estava no 'old' (envio falhado)
                          OU se ela já pertence ao jogo
                        --}}
                        @if( in_array($category->id, old('categories', $gameCategoryIds)) ) selected @endif
                    >
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
    
<fieldset>
    <legend>Imagens da Galeria</legend>
    
    <label>Imagens Atuais:</label>
    <div class="current-gallery-images" style="display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; background: #f4f4f4; padding: 10px; border-radius: 5px;">
        {{-- 1. Mostra as imagens que já existem no banco --}}
        @forelse ($game->images as $image)
            <img src="{{ Storage::url($image->image_url) }}" alt="Imagem da galeria" width="100" style="height: 100px; object-fit: cover; border-radius: 5px;">
        @empty
            <p style="margin: 0;">Este jogo ainda não tem imagens na galeria.</p>
        @endforelse
    </div>

    <div class="form-group">
        <label for="images">
            <strong>Substituir Galeria</strong> (Selecione novos arquivos)
        </label>
        <p style="font-size: 0.9em; color: #666; margin-top: 0;">
            <strong>Atenção:</strong> Enviar novos arquivos aqui irá **apagar todas as imagens atuais** e substituí-las pelas novas. Deixe em branco para não alterar nada.
        </p>
        
        {{-- 2. O input para enviar os novos arquivos --}}
        <input type="file" id="images" name="images[]" multiple>
        
        @error('images') <small class="error-message">{{ $message }}</small> @enderror
        @error('images.*') <small class="error-message">{{ $message }}</small> @enderror
    </div>
</fieldset>
    
    <button type="submit">Atualizar Jogo</button>

{{-- 8. CORRIGIDO: Removida a tag </form> duplicada que estava aqui --}}
</form>