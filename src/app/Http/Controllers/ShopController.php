<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    //店舗情報一覧取得
    public function getShops()
    {
        // 現在認証中のユーザーを取得
        $user = Auth::user();

        // ユーザーがLikeしている店舗のIDを取得
        $likedShops = $user->likes->pluck('shop_id')->toArray();

        // 全店舗の情報を取得し、各店舗にisLikedプロパティを追加
        $shops = Shop::all()->map(function ($shop) use ($likedShops) {
            $shop->isLiked = in_array($shop->id, $likedShops);
            return $shop;
        });

        // ジャンルの重複を排除した配列を作成
        $genres = Shop::pluck('genre')->unique()->values()->all();

        // レスポンスをJSON形式で返却
        return response()->json(compact('shops', 'genres'), 200);
    }


    public function detailShow($id)
    {
        $shop = Shop::find($id);

        if ($shop) {
            return response()->json(compact('shop'),200);
        } else {
            return response()->json(['message' => 'Shop not found'], 404);
        }
    }
}
