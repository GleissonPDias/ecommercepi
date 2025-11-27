@extends('layouts.admin')

@section('title', 'Gerenciar requisitos: ' .$product->name)

@section('content')
<body>

    <form class="form-carrossel" action="{{ route('admin.products.requirements.store', $product) }}" method="POST">
        @csrf

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="grid-2">
            <fieldset class="form-field">
                <legend>Requisitos Mínimos (Obrigatório)</legend>

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

            <fieldset class="form-field">
                <legend>Requisitos Recomendados (Opcional)</legend>

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
        <br>

        <button class="btn-create" type="submit">Salvar Requisitos</button>
    </form>

</body>
@endsection