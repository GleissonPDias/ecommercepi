@extends('layouts.admin')

@section('title', 'Gerenciar Plataformas')

@section('content')

<body>
    <div class="container2">
        <div class="header2">
            <h1>Lista de Plataformas</h1>
            <a href="{{route('admin.platforms.create')}}" class="btn-create">Adicionar nova Plataforma</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Slug</th>
                        <th>Qtd de jogos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
            <tbody>
                @forelse ($platforms as $platform)
                <tr class="table-lines">
                    <td>{{$platform->id}}</td>
                    <td>{{$platform->name}}</td>
                    <td>{{$platform->slug}}</td>
                    <td>{{$platform->products->count()}}</td>
                    <td class="actions">
                        <a class="btn-editar" href="{{route('admin.platforms.edit', $platform)}}">Editar</a>
                        <form action="{{route('admin.platforms.destroy', $platform)}}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn-excluir" type="submit" onclick="return confirm('Tem certeza que quer excluir esta plataforma?')">Deletar</button>
                        </form>
                    </td>
                </tr>
             @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Nenhuma plataforma cadastrada.
                    </td>
                </tr>
            @endforelse
            </tbody>
            </table>
        </div>
    </div>
</body>
@endsection