<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model {

    protected $fillable=[
        'title',
        'about',
        'cover_url',
        'release_date',
        'age_rating',
        'developer_id',
        'publisher_id'
    ];


    public function developer(): BelongsTo {
        return $this->belongsTo(Developer::class);
    }
    public function publisher(): BelongsTo {
        return $this->belongsTo(Publisher::class);
    }
    public function baseGame(): BelongsTo {
        return $this->belongsTo(Game::class, 'base_game_id');
    }
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }
    public function images(): HasMany {
        return $this->hasMany(GameImage::class);
    }
    public function dlcs(): HasMany {
        return $this->hasMany(Game::class, 'base_game_id');
    }
    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class, 'category_game');
    }
    public function favoritedByUsers(): BelongsToMany {
        return $this->belongsToMany(User::class, 'user_favorites');
    }
}
