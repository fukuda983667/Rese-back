<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    // ログイン中のベンダーユーザの店舗情報を取得
    public function getMyShops()
    {
        $user = Auth::user();

        // ベンダーのユーザーIDに関連する店舗情報を取得
        $shops = Shop::where('user_id', $user->id)->get();

        return response()->json([
            'shops' => $shops,
        ], 200);
    }
}
