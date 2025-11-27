@extends('layouts.admin')

@section('title', 'Gerenciar Cupons')

@section('content')
<body>
    <div class="container2">
        <div class="header2">
            <h2>Gerenciamento de Cupons</h2>
            <a href="{{ route('admin.coupons.create') }}" class="btn-create">Adicionar Novo Cupom</a>
        </div>

        @if (session('success'))
            <div class="alert-success" style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Valor</th>
                        <th>Expira em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($coupons as $coupon)
                        <tr class="table-lines">
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->type }}</td>
                            <td>
                                {{-- Formata o valor corretamente --}}
                                @if($coupon->type == 'percentage')
                                    {{ $coupon->value }}%
                                @else
                                    R$ {{ number_format($coupon->value, 2, ',', '.') }}
                                @endif
                            </td>
                            <td>
                                {{-- Formata a data --}}
                                {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Não expira' }}
                            </td>
                            <td>
                                {{-- Botão Editar --}}
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-editar">
                                    Editar
                                </a>
                                
                                {{-- Formulário Excluir --}}
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" style="display:inline; margin-left: 5px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-excluir" onclick="return confirm('Tem certeza que quer excluir este cupom?')">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 20px;">
                                Nenhum cupão encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Links de Paginação --}}
        <div class="pagination-links" style="margin-top: 20px;">
            {{ $coupons->links() }}
        </div>
    </div>
</body>
@endsection