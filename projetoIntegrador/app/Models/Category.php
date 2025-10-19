<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function games(): BelongsToMany {
    return $this->belongsToMany(Game::class, 'category_game');
}
}
