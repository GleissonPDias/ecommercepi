@extends('layouts.admin')

@section('title', 'Gerir Chaves: ' . $product->name)

@section('content')
    <h2>Gerir Stock de Chaves</h2>
    {{-- Mostra a que produto estas chaves pertencem --}}
    <p>Produto: <strong>{{ $product->name }} (ID: {{ $product->id }})</strong></p>

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
    <div class="form-container" style="background: #f9f9f9; border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
        <h4>Adicionar Novas Chaves</h4>
        <p>Cole a sua lista de chaves abaixo (uma chave por linha).</p>

        <form action="{{ route('admin.products.keys.store', $product) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="keys_list">Lista de Chaves:</label>
                <textarea name="keys_list" id="keys_list" rows="10" 
                          style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" 
                          placeholder="CHAVE123-ABCDE-45678&#10;CHAVE456-FGHIJ-90123&#10;CHAVE789-KLMNO-45678"></textarea>
            </div>
            <button type="submit" class="btn-primary" style="background: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px;">
                Adicionar Chaves ao Stock
            </button>
        </form>
    </div>

    {{-- ============================================= --}}
    {{-- PARTE 2: TABELA DE CHAVES EXISTENTES --}}
    {{-- ============================================= --}}
    <h3 style="margin-top: 30px;">Stock Atual ({{ $keys->total() }} chaves)</h3>
    <table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background: #f4f4f4;">
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">ID</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Chave (Key String)</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Status</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Vendida para (User ID)</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Ação</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($keys as $key)
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $key->id }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><code>{{ $key->key_value }}</code></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        @if ($key->is_sold)
                            <span style="color: #dc3545; font-weight: bold;">Vendida</span>
                        @else
                            <span style="color: #28a745; font-weight: bold;">Disponível</span>
                        @endif
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $key->user_id ?? 'N/A' }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        @if (!$key->is_sold)
                            <form method="POST" action="{{ route('admin.keys.destroy', $key) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-excluir" 
                                        onclick="return confirm('Tem certeza que quer apagar esta chave?')"
                                        style="background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px;">
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

    {{-- Links de Paginação --}}
    <div class="pagination-links" style="margin-top: 20px;">
        {{ $keys->links() }}
    </div>

@endsection