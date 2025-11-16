@extends('layouts.admin')
@section('title', 'Criar Novo Cupão')

@section('content')
    <h2>Criar Novo Cupão</h2>

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
    <form action="{{ route('admin.coupons.store') }}" method="POST" class="admin-form" style="max-width: 600px; background: #f9f9f9; padding: 20px; border-radius: 8px;">
        @csrf
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="code" style="display: block; margin-bottom: 5px; font-weight: bold;">Código do Cupão (Ex: NATAL10)</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>
        
        <div class="form-group" style="margin-bottom: 15px;">
            <label for="type" style="display: block; margin-bottom: 5px; font-weight: bold;">Tipo de Desconto</label>
            <select name="type" id="type" required style="width: 100%; padding: 8px; box-sizing: border-box;">
                <option value="percentage" @if(old('type') == 'percentage') selected @endif>Percentagem (%)</option>
                <option value="fixed" @if(old('type') == 'fixed') selected @endif>Valor Fixo (R$)</option>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="value" style="display: block; margin-bottom: 5px; font-weight: bold;">Valor (Ex: 10 para 10% ou 10.50 para R$10,50)</label>
            <input type="number" id="value" name="value" value="{{ old('value') }}" step="0.01" min="0" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <label for="expires_at" style="display: block; margin-bottom: 5px; font-weight: bold;">Data de Expiração (Opcional)</label>
            <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}" style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>
        
        <button type="submit" class="btn-primary" style="background: #28a745; color: white; padding: 10px 15px; border: none; cursor: pointer; border-radius: 5px;">Criar Cupão</button>
    </form>
@endsection