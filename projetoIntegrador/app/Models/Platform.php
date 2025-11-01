<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importe o HasMany

class Platform extends Model
{
    use HasFactory;

    /**
     * A CORREÇÃO "INVISÍVEL" ESTÁ AQUI.
     * Isso permite que os campos 'name' e 'slug' sejam salvos
     * através do método Platform::create().
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * (Bônus: Adicione seu relacionamento)
     * Uma plataforma pode ter muitos produtos.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}