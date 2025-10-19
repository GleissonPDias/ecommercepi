<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisitos do Jogo</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        form { max-width: 800px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        button { background: #007bff; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>

    <form action="{{ route('products.requirements.store', $product) }}" method="POST">
        @csrf

        <h1>Gerenciar Requisitos para: {{ $product->name }}</h1>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="grid">
            <fieldset>
                <legend><h2>Requisitos Mínimos (Obrigatório)</h2></legend>

                <div class="form-group">
                    <label for="min_os">Sistema Operacional</label>
                    <input type="text" id="min_os" name="min_os" value="{{ old('min_os', $min->os) }}" required>
                    @error('min_os') <small style="color:red">{{ $message }}</small> @enderror
                </div>
                
                <div class="form-group">
                    <label for="min_processor">Processador</label>
                    <input type="text" id="min_processor" name="min_processor" value="{{ old('min_processor', $min->processor) }}" required>
                    @error('min_processor') <small style="color:red">{{ $message }}</small> @enderror
                </div>
                
                <div class="form-group">
                    <label for="min_memory">Memória</label>
                    <input type="text" id="min_memory" name="min_memory" value="{{ old('min_memory', $min->memory) }}" required>
                    @error('min_memory') <small style="color:red">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="min_graphics">Placa de Vídeo</label>
                    <input type="text" id="min_graphics" name="min_graphics" value="{{ old('min_graphics', $min->graphics) }}" required>
                    @error('min_graphics') <small style="color:red">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="min_storage">Armazenamento</label>
                    <input type="text" id="min_storage" name="min_storage" value="{{ old('min_storage', $min->storage) }}" required>
                    @error('min_storage') <small style="color:red">{{ $message }}</small> @enderror
                </div>
            </fieldset>

            <fieldset>
                <legend><h2>Requisitos Recomendados (Opcional)</h2></legend>

                <div class="form-group">
                    <label for="rec_os">Sistema Operacional</label>
                    <input type="text" id="rec_os" name="rec_os" value="{{ old('rec_os', $rec->os) }}">
                </div>
                
                <div class="form-group">
                    <label for="rec_processor">Processador</label>
                    <input type="text" id="rec_processor" name="rec_processor" value="{{ old('rec_processor', $rec->processor) }}">
                </div>
                
                <div class="form-group">
                    <label for="rec_memory">Memória</label>
                    <input type="text" id="rec_memory" name="rec_memory" value="{{ old('rec_memory', $rec->memory) }}">
                </div>

                <div class="form-group">
                    <label for="rec_graphics">Placa de Vídeo</label>
                    <input type="text" id="rec_graphics" name="rec_graphics" value="{{ old('rec_graphics', $rec->graphics) }}">
                </div>

                <div class="form-group">
                    <label for="rec_storage">Armazenamento</label>
                    <input type="text" id="rec_storage" name="rec_storage" value="{{ old('rec_storage', $rec->storage) }}">
                </div>
            </fieldset>
        </div>

        <button type="submit">Salvar Requisitos</button>
    </form>

</body>
</html>