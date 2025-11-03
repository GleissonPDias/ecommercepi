<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameImage extends Model
{
    use HasFactory;
    
    // Permite que 'url_imagem' e 'order' sejam preenchidos
    protected $fillable = ['image_url', 'order'];

    // Define que não vamos usar 'created_at' e 'updated_at' nesta tabela
    public $timestamps = false;

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}