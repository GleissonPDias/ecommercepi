<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // 👈 Importante
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponManagementController extends Controller // 👈 Nome da classe correto
{
    /**
     * Mostra a lista de todos os cupões. (READ)
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Mostra o formulário para criar um novo cupão. (CREATE)
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Salva o novo cupão no banco de dados. (CREATE)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);
        Coupon::create($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Cupão criado com sucesso!');
    }

    /**
     * Mostra o formulário para editar um cupão. (UPDATE)
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Atualiza o cupão no banco de dados. (UPDATE)
     */
    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code' => ['required', 'string', Rule::unique('coupons', 'code')->ignore($coupon->id)],
            'type' => ['required', Rule::in(['percentage', 'fixed'])],
            'value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
        ]);
        $coupon->update($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Cupão atualizado com sucesso!');
    }

    /**
     * Apaga o cupão do banco de dados. (DELETE)
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Cupão apagado com sucesso.');
    }
}