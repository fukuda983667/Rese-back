<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;


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

            // フルパスに変換
            $shop->image_url = Config::get('app.url') . '/storage/shop/' . $shop->image_url;

            // レビューの平均評価を計算し、小数点第二位で切り捨て
            $averageRating = Review::where('shop_id', $shop->id)
                                    ->avg('rating');
            $shop->rating = floor($averageRating * 10) / 10;
            return $shop;
        });


        // 予約情報を取得し、予約時間でソート
        $reservations = $user->reservations()->with('shop')->orderBy('reservation_time')->get();

        // 現在時刻を取得
        $now = Carbon::now();

        // 予約情報と来店履歴情報に分ける
        $futureReservations = [];
        $pastReservations = [];

        foreach ($reservations as $reservation) {
            if ($reservation->reservation_time > $now) {
                $futureReservations[] = $reservation; // 予約情報
            } else {
                $pastReservations[] = $reservation; // 来店履歴情報
            }
        }

        // 予約情報のIDを1から順に付ける
        $futureReservations = collect($futureReservations)->map(function ($reservation, $index) {
            $reservation->new_id = $index + 1;
            return $reservation;
        });

        // 来店履歴情報のIDを新しい時間から順に付ける
        $pastReservations = collect($pastReservations)->sortByDesc('reservation_time')->values()->map(function ($reservation, $index) {
            $reservation->new_id = $index + 1;
            return $reservation;
        });

        return response()->json([
            'likes' => $likes,
            'reservations' => $futureReservations,
            'visitHistory' => $pastReservations,
        ], 200);
    }
}
