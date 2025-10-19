<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{

        protected $fillable=[
            'name',
            'slug'
    ];

    public function products(): HasMany {
    return $this->hasMany(Product::class);
}
}
