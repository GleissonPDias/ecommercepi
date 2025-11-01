<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{


    protected $fillable=[
        'name',
    ];

    public function games(): HasMany {
    return $this->hasMany(Game::class);
}
}
