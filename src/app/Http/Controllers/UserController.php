<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class UserController extends Controller
{
    // フロントのnuxt3でnuxt-auth-sanctumモジュールのメソッド使用すると勝手に/userを叩く
    public function getUser(Request $request)
    {
        // 認証されたユーザーを取得
        $user = $request->user();
        // お気に入り店舗も取得
        // $user->load('favorites.shop');

        // ユーザー情報をログに記録（デバッグ用）
        \Log::info($user);

        // ユーザー情報を返す
        return response()->json($user,200);
    }


    // 認証中ユーザーのマイページ情報を取得
    public function getMyPage() {
        $user = Auth::user();

        // お気に入り店舗と予約情報を取得
        $likes = $user->likeShops->map(function ($shop) {
            $shop->isLiked = true;
            return $shop;
        });

        // 予約情報を予約時間でソート。フロントで予約が早い順に表示したいから。
        $reservations = $user->reservations()->with('shop')->orderBy('reservation_time')->get();

        // IDを1から振り直す。ユーザが登録中の予約に番号振り(フロントで処理してもいいけどコードを増やしたくない。)
        // 可能な限りデータの処理はバックでやっときたい。
        $reservations = $reservations->map(function ($reservation, $index) {
            $reservation->new_id = $index + 1;
            return $reservation;
        });

        return response()->json([
            'likes' => $likes,
            'reservations' => $reservations,
        ], 200);
    }
}
