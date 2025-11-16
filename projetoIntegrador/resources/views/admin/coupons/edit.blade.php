@extends('layouts.admin')
@section('title', 'Editar Cupão: ' . $coupon->code)

@section('content')
    <h2>Editar Cupão: {{ $coupon->code }}</h2>

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

    {{-- O formulário aponta para 'update' e usa o método 'PUT' --}}
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="admin-form" style="max-width: 600px; background: #f9f9f9; padding: 20px; border-radius: 8px;">
        @csrf
        @method('PUT')
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="code" style="display: block; margin-bottom: 5px; font-weight: bold;">Código do Cupão</label>
            {{-- Preenche com o valor antigo (se houver erro) ou o valor do banco --}}
            <input type="text" id="code" name="code" value="{{ old('code', $coupon->code) }}" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="type" style="display: block; margin-bottom: 5px; font-weight: bold;">Tipo de Desconto</label>
            <select name="type" id="type" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                <option value="percentage" @if(old('type', $coupon->type) == 'percentage') selected @endif>Percentagem (%)</option>
                <option value="fixed" @if(old('type', $coupon->type) == 'fixed') selected @endif>Valor Fixo (R$)</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="value" style="display: block; margin-bottom: 5px; font-weight: bold;">Valor</label>
            <input type="number" id="value" name="value" value="{{ old('value', $coupon->value) }}" step="0.01" min="0" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="expires_at" style="display: block; margin-bottom: 5px; font-weight: bold;">Data de Expiração (Opcional)</label>
            {{-- Formata a data para o input HTML 'date' (Y-m-d) --}}
            <input type="date" id="expires_at" name="expires_at" 
                   value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '') }}" 
                   style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>
        
        <button type="submit" class="btn-primary" style="background: #007bff; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px;">Atualizar Cupão</button>
    </form>
@endsection