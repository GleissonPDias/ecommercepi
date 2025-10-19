<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameKey extends Model
{
    public function product(): BelongsTo {
    return $this->belongsTo(Product::class);
}
public function user(): BelongsTo {
    return $this->belongsTo(User::class);
}
public function orderItem(): BelongsTo {
    return $this->belongsTo(OrderItem::class);
}
}
