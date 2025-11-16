<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    /**
     * Valida o cupão e guarda-o na sessão.
     */
    public function apply(Request $request)
    {
        // 1. Valida o input (o código do cupão)
        $request->validate([
            'code' => 'required|string'
        ]);

        // 2. Procura o cupão no banco de dados
        $coupon = Coupon::where('code', $request->code)->first();

        // 3. Valida se o cupão é válido
        if (!$coupon) {
            return back()->with('error', 'Cupão inválido ou não encontrado.');
        }
        if ($coupon->expires_at && $coupon->expires_at < now()) {
            return back()->with('error', 'Este cupão expirou.');
        }
        // (Pode adicionar mais lógicas aqui, como 'max_uses')

        // 4. Se for válido, guarda-o na sessão
        session(['coupon' => $coupon]);

        return back()->with('success', 'Cupão aplicado com sucesso!');
    }

    /**
     * Remove o cupão da sessão.
     */
    public function remove()
    {
        // "Esquece" o cupão que estava guardado
        session()->forget('coupon');

        return back()->with('success', 'Cupão removido.');
    }
}
