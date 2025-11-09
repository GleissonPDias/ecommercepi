<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Jogos</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            padding: 30px;
            background-color: #f6f8fa;
            color: #24292e;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e1e4e8;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e1e4e8;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        h1 {
            margin: 0;
        }
        .btn-create {
            background-color: #2ea44f;
            color: white;
            padding: 10px 16px;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-create:hover {
            background-color: #2c974b;
        }
        
        /* Estilo da Tabela */
        .table-wrapper {
            overflow-x: auto; /* Para responsividade em telas pequenas */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dfe2e5;
            padding: 10px 12px;
            text-align: left;
            vertical-align: middle;
            font-size: 14px;
        }
        th {
            background-color: #f6f8fa;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* Imagem de Capa na Tabela */
        .table-cover-img {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        /* Botões de Ação */
        .actions {
            display: flex;
            gap: 10px;
        }
        .btn-action {
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 12px;
            font-weight: 500;
        }
        .btn-edit {
            background-color: #007bff;
        }
        .btn-delete {
            background-color: #d73a49;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }
        
        /* Mensagens e Paginação */
        .alert-success {
            background: #d4edda; color: #155724;
            padding: 12px; border-radius: 6px; margin-bottom: 20px;
        }
        .pagination {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Lista de Jogos do Catálogo</h1>
            <a href="{{ route('admin.games.create') }}" class="btn-create">Adicionar Novo Jogo</a>
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
                        <tr>
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
                                {{-- Link para a futura página de edição --}}
                                <a href="{{route('admin.games.edit', $game)}}" class="btn-action btn-edit">Editar</a>
                                
                                {{-- Formulário de Delete --}}
                                <form method="POST" action="{{route('admin.games.destroy', $game)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Tem certeza que quer excluir este jogo?')" class="btn-action btn-delete">Deletar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">
                                Nenhum jogo cadastrado ainda.
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
</body>
</html>