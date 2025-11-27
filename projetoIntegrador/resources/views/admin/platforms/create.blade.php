@extends('layouts.admin')

@section('title', 'Criar Plataforma')

@section('content')

<form class="form-carrossel" action="{{ route('admin.platforms.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" required /> 
    </div>
        <button class="btn-create" type="submit">Criar</button>
</form>
@endsection