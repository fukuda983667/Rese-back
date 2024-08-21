<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Shop;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
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