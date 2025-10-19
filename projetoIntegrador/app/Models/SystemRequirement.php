<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRequirement extends Model
{
    protected $fillable=[
        'product_id',
        'type',
        'os',
        'processor',
        'memory',
        'graphics',
        'storage'
    ];
    
    public function product(): BelongsTo 
    {
    return $this->belongsTo(Product::class);
}
}
