<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\GameKey;

class User extends Authenticatable //implements MustVerifyEmail
{
    public function orders(): HasMany {
        return $this->hasMany(Order::class);
    }
    public function paymentMethods(): HasMany {
        return $this->hasMany(PaymentMethod::class);
    }
    public function cartItems(): HasMany {
        return $this->hasMany(CartItem::class);
    }
    public function library(): HasMany {
        return $this->hasMany(GameKey::class)->where('is_sold', true);
    }
    public function favorites(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'user_favorites', 'user_id', 'product_id');
    }
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'cpf',
        'phone_number',
        'username',
        'birth_date',
        'profile_photo_path',
        'email',
        'password',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean'
        ];
    }
    public function isAdmin(): bool
{
    return (bool) $this->is_admin;
}
}
