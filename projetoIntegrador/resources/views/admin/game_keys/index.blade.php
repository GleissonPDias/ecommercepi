@extends('layouts.admin')

@section('title', 'Gerir stock de keys: ' . $product->name )

@section('content')

    {{-- Bloco para mostrar mensagens de sucesso ou erro --}}
    @if (session('success'))
        <div class="alert-success" style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-error" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">{{ session('error') }}</div>
    @endif
    @error('keys_list')
        <div class="alert-error" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">{{ $message }}</div>
    @enderror

    {{-- ============================================= --}}
    {{-- PARTE 1: FORMULÁRIO PARA ADICIONAR CHAVES --}}
    {{-- ============================================= --}}
    <div class="form-carrossel">
        <h3>Adicionar novas keys</h3>
        <label>Cole a sua lista de keys abaixo (uma chave por linha).</label>

        <form action="{{ route('admin.products.keys.store', $product) }}" method="POST">
            @csrf
            <div class="form-group">
                <textarea name="keys_list" id="keys_list" rows="10"  
                          placeholder="CHAVE123-ABCDE-45678&#10;CHAVE456-FGHIJ-90123&#10;CHAVE789-KLMNO-45678"></textarea>
            </div>
            <button type="submit" class="btn-create">
                Adicionar keys ao estoque
            </button>
        </form>
    </div>

    {{-- ============================================= --}}
    {{-- PARTE 2: TABELA DE CHAVES EXISTENTES --}}
    {{-- ============================================= --}}
    <div class="form-carrossel">
        <h3>Stock Atual ({{ $keys->total() }} chaves)</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Chave (Key String)</th>
                    <th>Status</th>
                    <th>Vendida para (User ID)</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($keys as $key)
                    <tr class="table-lines">
                        <td>{{ $key->id }}</td>
                        <td><code>{{ $key->key_value }}</code></td>
                        <td>
                            @if ($key->is_sold)
                                <span style="color: #dc3545; font-weight: bold;">Vendida</span>
                            @else
                                <span style="color: #28a745; font-weight: bold;">Disponível</span>
                            @endif
                        </td>
                        <td>{{ $key->user_id ?? 'N/A' }}</td>
                        <td>
                            @if (!$key->is_sold)
                                <form method="POST" action="{{ route('admin.keys.destroy', $key) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-excluir" 
                                            onclick="return confirm('Tem certeza que quer apagar esta chave?')">
                                        Excluir
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 10px; border: 1px solid #ddd; text-align: center;">Nenhuma chave cadastrada para este produto.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Links de Paginação --}}
    <div class="pagination-links" style="margin-top: 20px;">
        {{ $keys->links() }}
    </div>

@endsection