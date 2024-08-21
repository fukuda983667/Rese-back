<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    // お気に入りのトグル（追加または削除）
    public function toggleLike(Request $request)
    {
        $userId = Auth::id();
        $shopId = $request->input('shop_id');
        $requestUserId = $request->input('user_id');

        // リクエスト内の user_id と Auth::id() を確認
        if ($userId !== $requestUserId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // お気に入りのトグル処理
        $like = Like::where('user_id', $userId)
                            ->where('shop_id', $shopId)
                            ->first();

        if ($like) {
            // お気に入りが存在する場合は削除
            $like->delete();
            return response()->json(['message' => 'Shop removed from likes'], 200);
        } else {
            // お気に入りが存在しない場合は追加
            Like::create([
                'user_id' => $userId,
                'shop_id' => $shopId
            ]);
            return response()->json(['message' => 'Shop added to likes'], 200);
        }
    }
}
