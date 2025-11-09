<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model {

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'payment_method_id',
        'coupon_id'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function paymentMethod(): BelongsTo {
        return $this->belongsTo(PaymentMethod::class);
    }
    public function coupon(): BelongsTo {
        return $this->belongsTo(Coupon::class);
    }
    public function items(): HasMany {
        return $this->hasMany(OrderItem::class);
    }
}