<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

        protected $fillable = [
        'user_id',
        'shop_id',
        'review_title',
        'review_text',
        'rating'
    ];


    // Userモデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Shopモデルとのリレーション
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
