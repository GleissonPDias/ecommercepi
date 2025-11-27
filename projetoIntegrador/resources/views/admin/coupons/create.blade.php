@extends('layouts.admin')

@section('title', 'Criar Novo Cupom')

@section('content')

<body>

    {{-- Mostra erros de validação (se houver) --}}
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

    {{-- O formulário aponta para a rota 'store' --}}
    <form class="form-carrossel" action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="code">Código do Cupom (Ex: NATAL10)</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required>
        </div>
        
        <div class="form-group">
            <label for="type">Tipo de Desconto</label>
            <select name="type" id="type" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                <option value="percentage" @if(old('type') == 'percentage') selected @endif>Porcentagem (%)</option>
                <option value="fixed" @if(old('type') == 'fixed') selected @endif>Valor Fixo (R$)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="value">Valor (Ex: 10 para 10% ou 10.50 para R$10,50)</label>
            <input type="number" id="value" name="value" value="{{ old('value') }}" step="0.01" min="0" required>
        </div>

        <div class="form-group">
            <label for="expires_at">Data de Expiração (Opcional)</label>
            <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
        </div>
        
        <button type="submit" class="btn-create">Criar Cupom</button>
    </form>
</body>
@endsection