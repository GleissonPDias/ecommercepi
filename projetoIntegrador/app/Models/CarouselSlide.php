<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CarouselSlide extends Model
{
    use HasFactory;

    protected $fillable =[
        'name'
    ];
    
    // Um slide pode ter MUITOS produtos
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'carousel_slide_product')
                    ->withPivot('slot'); // Importante: nos permite ler a coluna 'slot'
    }
}