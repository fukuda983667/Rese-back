<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    //レビュー投稿機能
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'shop_id' => 'required|exists:shops,id',
            'review_title' => 'required|string|max:20',
            'review_text' => 'required|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review = Review::create($validatedData);

        return response()->json(['message' => 'review created successfully', 'review' => $review], 201);
    }


    // 店舗ごとのレビュー一覧を取得
    public function getReviewsByShop($id)
    {
        // 店舗名を取得
        $shopName = Shop::where('id', $id)->value('name');

        if (!$shopName) {
            return response()->json(['message' => 'Shop not found.'], 404);
        }

        $reviews = Review::where('shop_id', $id)
            ->with([
                'user' => function($query) {
                    $query->select('id', 'name'); // ユーザー名のみ取得
                },
            ])
            ->get();

        if ($reviews->isEmpty()) {
            return response()->json(['message' => 'No reviews found for this shop.'], 404);
        }

        return response()->json(['shop_name' => $shopName, 'reviews' => $reviews], 200);
    }
}
