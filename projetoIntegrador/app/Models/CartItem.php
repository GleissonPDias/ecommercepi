<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
USE Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class CartItem extends Model
{

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
