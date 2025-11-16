<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GameMode extends Model
{
    use HasFactory;
    

    public function games(): BelongsToMany {
        return $this->belongsToMany(Game::class, 'game_game_mode');
    }
}
