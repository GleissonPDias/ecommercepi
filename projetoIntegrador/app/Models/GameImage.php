<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameImage extends Model
{
    public function game(): BelongsTo {
    return $this->belongsTo(Game::class);
}
}
