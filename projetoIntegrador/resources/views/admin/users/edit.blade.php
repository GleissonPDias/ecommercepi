@extends('layouts.admin')

@section('title', 'Editar Usuário: ' . $user->name)

@section('content')

    <form class="form-carrossel" action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid-2">
            {{-- Campo Nome --}}
            <div class="form-group">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <small class="error-message">{{ $message }}</small> @enderror
            </div>

            {{-- Campo Email --}}
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <small class="error-message">{{ $message }}</small> @enderror
            </div>
        </div>

        {{-- 👇 O CAMPO MAIS IMPORTANTE 👇 --}}
        <div class="form-group">
            <label for="is_admin">Função (Permissão):</label>
            <select name="is_admin" id="is_admin">
                <option value="0" @if(!old('is_admin', $user->is_admin)) selected @endif>
                    Cliente
                </option>
                <option value="1" @if(old('is_admin', $user->is_admin)) selected @endif>
                    Administrador
                </option>
            </select>
            @error('is_admin') <small class="error-message">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn-create">Salvar Alterações</button>
    </form>
@endsection