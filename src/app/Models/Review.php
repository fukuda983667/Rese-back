<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Review extends Model
{
    use HasFactory;

        protected $fillable = [
        'user_id',
        'shop_id',
        'image_url',
        'review_text',
        'rating'
    ];

    public function getImageUrlAttribute($value)
    {
        if ($value) {
            $baseUrl = Config::get('app.url') . '/storage/review/';
            return $baseUrl . $value;
        }

        return null; // image_url が null の場合はそのまま返す
    }

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
