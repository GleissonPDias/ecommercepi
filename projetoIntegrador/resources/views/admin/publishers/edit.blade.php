@extends('layouts.admin')

@section('title', 'Editar Publisher: ' .$publisher->name)

@section('content')

<form class="form-carrossel" action="{{ route('admin.publishers.update', $publisher )}}" method="POST">
    @method('PUT')
    @csrf
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{$publisher->name}}" />
</div>
        <button class="btn-create" type="submit">Atualizar</button>
</form> 
@endsection