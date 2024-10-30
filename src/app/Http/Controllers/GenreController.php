<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;

class GenreController extends Controller
{
    // 全ジャンルのリストを取得
    public function getGenres()
    {
        $genres = Genre::all();

        return response()->json(compact('genres'),200);
    }
}
