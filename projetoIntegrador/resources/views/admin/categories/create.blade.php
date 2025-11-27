@extends('layouts.admin')

@section('title', 'Criar Categoria')

@section('content')

<form class="form-carrossel" action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" required />
    </div>
    <button class="btn-create" type="submit">Criar</button> 
</form>
@endsection