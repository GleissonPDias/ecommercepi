<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    protected $fillable = [
        'product_id',
        'order_id',
        'quantity',
        'price_at_purchase'
    ];

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
}
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
}
}
