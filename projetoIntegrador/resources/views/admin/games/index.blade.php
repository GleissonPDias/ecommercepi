@extends('layouts.admin')

@section('title', 'Gerenciar Jogos')

@section('content')

<body>
    <div class="container2">
        <div class="header2">
            <h1>Lista de Jogos do Catálogo</h1>
            <a href="{{ route('admin.games.create') }}" class="btn-create">Adicionar novo jogo</a>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Capa</th>
                        <th>Título</th>
                        <th>Desenvolvedor</th>
                        <th>Editor</th>
                        <th>Categorias</th>
                        <th>Imagens</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($games as $game)
                        <tr class="table-lines">
                            <td>
                                <img src="{{ Storage::url($game->cover_url) }}" alt="{{ $game->title }}" class="table-cover-img">
                            </td>
                            <td>{{ $game->title }}</td>
                            
                            {{-- Usamos '??' como segurança caso o dev seja deletado --}}
                            <td>{{ $game->developer->name ?? 'N/A' }}</td>
                            <td>{{ $game->publisher->name ?? 'N/A' }}</td>
                            
                            {{-- Puxa a contagem do 'withCount' --}}
                            <td>{{ $game->categories_count }}</td>
                            <td>{{ $game->images_count }}</td>
                            
                            <td class="actions">
                                <a class="btn-editar" href="{{route('admin.games.edit', $game)}}">Editar</a>
                                <form method="POST" action="{{route('admin.games.destroy', $game)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-excluir" type="submit" onclick="return confirm('Tem certeza que quer excluir este jogo?')">Deletar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">
                                Nenhum jogo cadastrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Links de Paginação --}}
        <div class="pagination">
            {{ $games->links() }}
        </div>

    </div>
    @endsection
</body>
</html>