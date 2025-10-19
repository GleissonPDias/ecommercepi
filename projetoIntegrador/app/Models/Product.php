<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model {

    protected $fillable = [
        'game_id',
        'platform_id',
        'name',
        'default_price',
        'current_price',
        'is_active',
        'is_featured_main',
        'is_featured_secondary',
    ];

    public function game(): BelongsTo {
        return $this->belongsTo(Game::class);
    }
    public function platform(): BelongsTo {
        return $this->belongsTo(Platform::class);
    }
    public function gameKeys(): HasMany {
        return $this->hasMany(GameKey::class);
    }
    public function orderItems(): HasMany {
        return $this->hasMany(OrderItem::class);
    }
    public function cartItems(): HasMany {
        return $this->hasMany(CartItem::class);
    }
    public function systemRequirements(): HasMany {
        return $this->hasMany(SystemRequirement::class);
    }
    public function campaigns(): BelongsToMany {
        return $this->belongsToMany(Campaign::class, 'campaign_product')
                    ->withPivot('promotional_price');
    }
}