@extends('layouts.admin')

@section('title', 'Gerenciar Publishers')

@section('content')

<body>
    <div class="container2">
        <div class="header2">
            <h1>Lista de Publishers</h1>
            <a href="{{route('admin.publishers.create')}}" class="btn-create">Criar nova Publisher</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Qtd de jogos<th>
                        <th>Ações</th>
                    </tr>
                </thead>
            <tbody>
                @forelse ($publishers as $publisher)
                <tr class="table-lines">
                    <td>{{$publisher->id}}</td>
                    <td>{{$publisher->name}}</td>
                    <td>{{$publisher->games->count()}}</td>
                    <td class="actions">   
                        <a class="btn-editar" href="{{route('admin.publishers.edit', $publisher)}}">Editar</a>
                        <form action="{{route('admin.publishers.destroy', $publisher)}}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn-excluir" type="submit">Deletar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Nenhuma publisher cadastrada.
                    </td>
                </tr>
            @endforelse
            </tbody>
            </table> 
        </div>
    </div>
</body>
@endsection