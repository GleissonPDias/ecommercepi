@extends('layouts.admin')

@section('title', 'Gerenciar Desenvolvedores')

@section('content')

<body>
    <div class="container2">
        <div class="header2">   
            <h1>Lista de Desenvolvedores</h1>
            <a href="{{route('admin.developers.create')}}" class="btn-create">Criar novo desenvolvedor</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>    
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Qtd de jogos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
            <tbody>
                @forelse ($developers as $developer)
                <tr class="table-lines">
                    <td>{{$developer->id}}</td>
                    <td>{{$developer->name}}</td>
                    <td>{{$developer->games->count()}}</td>
                    <td class="actions">
                        <a class="btn-editar" href="{{route('admin.developers.edit', $developer)}}">Editar</a>
                        <form action="{{route('admin.developers.destroy', $developer)}}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn-excluir" type="submit" onclick="return confirm('Tem certeza que quer excluir este desenvolvedor?')">Deletar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">
                        Nenhum desenvolvedor cadastrado.
                    </td>
                </tr>
            @endforelse
            </tbody>
            </table> 
        </div>
    </div>
</body>
@endsection