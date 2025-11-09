@extends('layouts.admin')

@section('title', 'Criar Novo Usuário')

@section('content')
    <h2>Criar Novo Usuário</h2>

    {{-- O formulário aponta para a rota 'store' que o Route::resource criou --}}
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf {{-- Token de segurança obrigatório --}}

        {{-- Campo Nome --}}
        <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <small class-alias="error-message" style="color: red;">{{ $message }}</small> @enderror
        </div>

        {{-- Campo Email --}}
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <small class="error-message" style="color: red;">{{ $message }}</small> @enderror
        </div>
        
        {{-- Campo Senha --}}
        <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            @error('password') <small class="error-message" style="color: red;">{{ $message }}</small> @enderror
        </div>

        {{-- Campo Confirmar Senha --}}
        <div class="form-group">
            <label for="password_confirmation">Confirmar Senha:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            {{-- O Laravel trata o erro 'confirmed' no campo 'password' --}}
        </div>
        
        {{-- Campo de Permissão (Função) --}}
        <div class="form-group">
            <label for="is_admin">Função (Permissão):</label>
            <select name="is_admin" id="is_admin">
                <option value="0" @if(old('is_admin') == 0) selected @endif>
                    Cliente
                </option>
                <option value="1" @if(old('is_admin') == 1) selected @endif>
                    Administrador
                </option>
            </select>
            @error('is_admin') <small class="error-message" style="color: red;">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn-primary" style="background: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px;">Criar Usuário</button>
    </form>
@endsection