<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @extends('layouts.admin')

    @section('title', 'Gerenciar Produtos')

    @section('content')
</head>
<body>
    <div class="container2">
        <div class="header2">
            <h1>Meus Produtos</h1>
            <a href="{{ route('admin.products.create') }}" class="btn-create">Criar Novo Produto</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto</th>
                        <th>Jogo Base</th>
                        <th>Plataforma</th>
                        <th>Preço</th>
                        <th>Requisitos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="table-lines">
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->game->title }}</td>
                            <td>{{ $product->platform->name }}</td>
                            <td>R$ {{ number_format($product->current_price, 2, ',', '.') }}</td>
                            <td>
                                <a href="{{ route('admin.products.requirements.edit', $product) }}" class="btn btn-editar">
                                    Gerenciar Requisitos
                                </a>
                            </td>
                            <td class="actions">
                                    {{-- Link para a futura página de edição --}}
                                    <a href="{{route('admin.products.edit', $product)}}" class="btn-action btn-editar">Editar</a>

                                    <a href="{{ route('admin.products.keys.index', $product) }}" class="btn-gerenciar-chaves" style="background: #007bff; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 0.9em;">
                                    Gerir Chaves
                                    </a>
                                    
                                    {{-- Formulário de Delete --}}
                                    <form method="POST" action="{{route('admin.products.destroy', $product)}}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Tem certeza que quer excluir este produto?')" class="btn-action btn-excluir">Deletar</button>
                                    </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 20px;">
                                Nenhum produto cadastrado.
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
