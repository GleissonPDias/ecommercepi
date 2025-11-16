<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentMethod extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa.
     * (CORRIGIDO para bater com a sua migração)
     */
    protected $fillable = [
        'user_id',
        'gateway_token',        // 👈 Corrigido
        'card_brand',           // 👈 Corrigido
        'last_four_digits',     // 👈 Corrigido
        'is_default',           // 👈 Adicionado
        // 'expires_at_month',  (Removido, pois não está na sua migração)
        // 'expires_at_year',   (Removido)
    ];

    /**
     * Define o relacionamento com o Utilizador (o "dono" do cartão)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}