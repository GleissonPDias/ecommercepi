@extends('layouts.admin')

@section('title', 'Editar Desenvolvedor(a): ' .$developer->name)

@section('content')

<form class="form-carrossel" action="{{ route('admin.developers.update', $developer )}}" method="POST">
    @method('PUT')
    @csrf
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{$developer->name}}" />
    </div>
        <button class="btn-create" type="submit">Atualizar</button>
</form>
@endsection