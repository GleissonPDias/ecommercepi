<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Produtos</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .alert { padding: 15px; margin-bottom: 15px; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; border-radius: 5px; text-decoration: none; color: white; display: inline-block; }
        .btn-create { background: #007bff; }
        .btn-edit { background: #ffc107; color: black; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Meus Produtos</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <a href="{{ route('admin.products.create') }}" class="btn btn-create">Criar Novo Produto</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Jogo Base</th>
                    <th>Plataforma</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->game->title }}</td>
                        <td>{{ $product->platform->name }}</td>
                        <td>R$ {{ number_format($product->current_price, 2, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('admin.products.requirements.edit', $product) }}" class="btn btn-edit">
                                Gerenciar Requisitos
                            </a>
                        </td>
                        <td class="actions">
                                {{-- Link para a futura página de edição --}}
                                <a href="{{route('admin.products.edit', $product)}}" class="btn-action btn-edit">Editar</a>

                                <a href="{{ route('admin.products.keys.index', $product) }}" class="btn-gerenciar-chaves" style="background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 0.9em;">
                                Gerir Chaves
                                </a>
                                
                                {{-- Formulário de Delete --}}
                                <form method="POST" action="{{route('admin.products.destroy', $product)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Tem certeza que quer excluir este produto?')" class="btn-action btn-delete">Deletar</button>
                                </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Nenhum produto cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>