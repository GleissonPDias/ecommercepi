@extends('layouts.admin')

@section('title', 'Criar nova publisher')

@section('content')

<form class="form-carrossel" action="{{ route('admin.publishers.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="name">Nome</label>
        <input type="text" name="name" id="name" required />
    </div>
        <button class="btn-create" type="submit">Criar</button>
</form>
@endsection