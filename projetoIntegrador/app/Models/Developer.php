<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{

    protected $fillable=[
        'name'
    ];

    public function games(): HasMany {
    return $this->hasMany(Game::class);
}
}
