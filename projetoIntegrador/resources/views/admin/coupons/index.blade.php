{{-- 1. "Veste" o seu layout mestre de admin --}}
@extends('layouts.admin')

{{-- 2. Define o título da página --}}
@section('title', 'Gerenciar Cupões')

{{-- 3. Injeta o conteúdo principal --}}
@section('content')
    <h2>Gerenciamento de Cupões</h2>

    {{-- Link para a rota 'create' --}}
    <a href="{{ route('admin.coupons.create') }}" class="btn-primary" style="margin-bottom: 15px; display: inline-block; background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
        Adicionar Novo Cupão
    </a>

    {{-- Mostra mensagens de sucesso --}}
    @if (session('success'))
        <div class="alert-success" style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabela para listar os cupões --}}
    <table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background: #f4f4f4;">
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Código</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Tipo</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Valor</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Expira em</th>
                <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($coupons as $coupon)
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $coupon->code }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">{{ $coupon->type }}</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {{-- Formata o valor corretamente --}}
                        @if($coupon->type == 'percentage')
                            {{ $coupon->value }}%
                        @else
                            R$ {{ number_format($coupon->value, 2, ',', '.') }}
                        @endif
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {{-- Formata a data --}}
                        {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Não expira' }}
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        {{-- Botão Editar --}}
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-editar" style="background: #ffc107; color: #212529; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 0.9em;">
                            Editar
                        </a>
                        
                        {{-- Formulário Excluir --}}
                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" style="display:inline; margin-left: 5px;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-excluir" 
                                    style="background: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 4px; font-size: 0.9em;"
                                    onclick="return confirm('Tem certeza que quer excluir este cupão?')">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 10px; border: 1px solid #ddd; text-align: center;">Nenhum cupão encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Links de Paginação --}}
    <div class="pagination-links" style="margin-top: 20px;">
        {{ $coupons->links() }}
    </div>
@endsection