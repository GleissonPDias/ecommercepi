<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    public function user(): BelongsTo {
    return $this->belongsTo(User::class);
}
public function product(): BelongsTo {
    return $this->belongsTo(Product::class);
}
}
