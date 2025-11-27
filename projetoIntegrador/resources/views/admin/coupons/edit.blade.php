@extends('layouts.admin')

@section('title', 'Editar Cupom: ' . $coupon->code)

@section('content')

<body>
    @if ($errors->any())
        <div class="alert-error" style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
            <strong>Opa!</strong> Algo correu mal:
            <ul style="margin-top: 10px; list-style-position: inside;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-carrossel" action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="code">Código do Cupão</label>
            {{-- Preenche com o valor antigo (se houver erro) ou o valor do banco --}}
            <input type="text" id="code" name="code" value="{{ old('code', $coupon->code) }}" required >
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="type">Tipo de Desconto</label>
            <select name="type" id="type" required>
                <option value="percentage" @if(old('type', $coupon->type) == 'percentage') selected @endif>Porcentagem (%)</option>
                <option value="fixed" @if(old('type', $coupon->type) == 'fixed') selected @endif>Valor Fixo (R$)</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="value">Valor</label>
            <input type="number" id="value" name="value" value="{{ old('value', $coupon->value) }}" step="0.01" min="0" required>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="expires_at">Data de Expiração (Opcional)</label>
            {{-- Formata a data para o input HTML 'date' (Y-m-d) --}}
            <input type="date" id="expires_at" name="expires_at" 
                   value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}">
        </div>
        
        <button type="submit" class="btn-create" >Atualizar Cupom</button>
    </form>
</body>
@endsection