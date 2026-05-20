<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code',
        'address',
        'building',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 出品した商品
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // いいねした商品
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 投稿したコメント
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // 購入した商品
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

        /**
     * ユーザーがいいねした商品一覧
     */
    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes')
                    ->withTimestamps();
    }
}
