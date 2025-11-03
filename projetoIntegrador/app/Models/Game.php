<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Product;
use App\Models\GameImage;
use App\Models\Category;
use App\Models\User;

class Game extends Model {

    use HasFactory;

    protected $fillable=[
        'title',
        'about',
        'cover_url',
        'release_date',
        'age_rating',
        'developer_id',
        'publisher_id',
        'base_game_id'
    ];

    protected $casts = [
        // Isso converte a 'release_date' de string para um objeto de data
        'release_date' => 'datetime',
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
    public function product(): HasOne {
        return $this->hasOne(Product::class, 'game_id');
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
