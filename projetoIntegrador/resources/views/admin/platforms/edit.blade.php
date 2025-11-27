@extends('layouts.admin')

@section('title', 'Editar Plataforma: ' .$platform->name)

@section('content')

<form class="form-carrossel" action="{{ route('admin.platforms.update', $platform )}}" method="POST">
    @method('PUT')
    @csrf
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" value="{{$platform->name}}" />
    </div>
        <button class="btn-create" type="submit">Atualizar</button>
</form>
@endsection