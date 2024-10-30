<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'region',
        'genre',
        'description',
        'image_url',
    ];


    public function favorites()
    {
        return $this->hasMany(Like::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    // Regionとのリレーション
    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    // Genreとのリレーション
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
