<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @extends('layouts.admin')

    @section('title', 'Gerenciar Categorias')

    @section('content')

</head>
<body>
    <div class="container2">
        <div class="header2">    
            <h1>Minhas Categorias</h1>
            <a href="{{route('admin.categories.create')}}" class="btn-create">Criar nova categoria</a>
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
                    @forelse ($categories as $category)
                    <tr class="table-lines">
                        <td>{{$category->id}}</td>
                        <td>{{$category->name}}</td>
                        <td>{{$category->slug}}</td>
                        <td>{{$category->games->count()}}</td>
                        <td class="actions">
                            <a class="btn-editar" href="{{route('admin.categories.edit', $category)}}">Editar</a>
                            <form action="{{route('admin.categories.destroy', $category)}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button class="btn-excluir" type="submit" onclick="return confirm('Tem certeza que quer excluir esta categoria?')">Deletar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 20px;">
                            Nenhuma categoria cadastrada.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endsection
</body>
</html>