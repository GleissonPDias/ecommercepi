@extends('layouts.admin')

@section('title', 'Editar categoria: ' .$category->name)

@section('content')

<form class="form-carrossel" action="{{ route('admin.categories.update', $category )}}" method="POST">
    @method('PUT')
    @csrf
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{$category->name}}" />
    </div>
        <button class="btn-create" type="submit">Atualizar</button>
</form> 
@endsection