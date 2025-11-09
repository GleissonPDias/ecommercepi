@extends('layouts.admin')

@section('title', 'Gerenciar Usuários')

@section('content')
    <h2>Gerenciamento de Usuários</h2>

    <a href="{{ route('admin.users.create') }}" class="btn-primary" style="margin-bottom: 15px; display: inline-block;">
        Adicionar Novo Usuário
    </a>

    {{-- ============================================= --}}
    {{-- TABELA DE ADMINISTRADORES --}}
    {{-- ============================================= --}}
    <h3 style="margin-top: 20px;">Administradores</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Função</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop pela coleção $admins --}}
            @forelse ($admins as $user)
                <tr style="background-color: #f0fff4;"> {{-- (cor de fundo levemente verde) --}}
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span style="color: #28a745; font-weight: bold;">Admin</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-editar">
                            Editar
                        </a>

                        @if (auth()->user()->id !== $user->id)
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-excluir" 
                                        onclick="return confirm('Tem certeza que quer excluir este usuário?')">
                                    Excluir
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhum administrador encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ============================================= --}}
    {{-- TABELA DE CLIENTES --}}
    {{-- ============================================= --}}
    <h3 style="margin-top: 30px;">Clientes</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Função</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            {{-- Loop pela coleção $clients --}}
            @forelse ($clients as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span>Cliente</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-editar">
                            Editar
                        </a>

                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-excluir" 
                                    onclick="return confirm('Tem certeza que quer excluir este usuário?')">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Nenhum cliente encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- A paginação agora é dos $clients --}}
    <div class="pagination-links" style="margin-top: 20px;">
        {{ $clients->links() }}
    </div>
@endsection