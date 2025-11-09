<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameKey extends Model
{

    protected $fillable = [
        'product_id',
        'key_string',
        'is_sold',
        'user_id',
        'order_id',
        'order_item_id', // (Se você tiver esta coluna)
    ];


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
