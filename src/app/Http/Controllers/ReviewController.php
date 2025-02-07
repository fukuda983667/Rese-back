<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ReviewController extends Controller
{
    // 店舗ごとのレビュー一覧を取得
    public function getReviewsByShop($shopId)
    {
        // 店舗名を取得
        $shopName = Shop::where('id', $shopId)->value('name');

        if (!$shopName) {
            return response()->json(['message' => 'Shop not found.'], 404);
        }

        $reviews = Review::where('shop_id', $shopId)
            ->with([
                'user' => function($query) {
                    $query->select('id', 'name'); // ユーザー名のみ取得
                },
            ])
            ->get();

        return response()->json(['shop_name' => $shopName, 'reviews' => $reviews], 200);
    }

    // ユーザが投稿したレビューを取得
    public function getUserReview($shopId)
    {
        // ログイン中のユーザーを取得
        $user = Auth::user();

        // 指定されたショップIDとユーザーIDに基づいてレビューを取得
        $review = Review::where('shop_id', $shopId)
                        ->where('user_id', $user->id)
                        ->first();

        if (!$review) {
            return response()->json(['message' => 'レビュー投稿がありません'], 404);
        }

        return response()->json(['review' => $review], 200);
    }

    // ユーザのレビュー可否(来店履歴があるか)
    public function canUserReview($shopId)
    {
        $user = Auth::user();

        // ユーザーが過去にその店舗を予約したことがあるか確認
        $hasVisited = Reservation::where('user_id', $user->id)
                                ->where('shop_id', $shopId)
                                ->where('reservation_time', '<', now()) // 過去の予約
                                ->exists();

        return response()->json([
            'can_review' => $hasVisited,
            'message' => $hasVisited ? 'レビュー可能です' : '来店履歴がないためレビューできません'
        ], 200);
    }


    //レビュー投稿機能
    public function storeReview(ReviewRequest $request)
    {
        $userId = Auth::id();

        // 来店履歴があるか確認（過去の予約が存在するか）
        $hasVisited = Reservation::where('user_id', $userId)
            ->where('shop_id', $request->shop_id)
            ->where('reservation_time', '<', now()) // 過去の予約のみ
            ->exists();

        if (!$hasVisited) {
            return response()->json(['message' => '来店履歴がないためレビューできません'], 403);
        }

        // ユーザーがすでにレビューしているか確認
        $existingReview = Review::where('user_id', $userId)
                                ->where('shop_id', $request->shop_id)
                                ->first();

        if ($existingReview) {
            return response()->json(['message' => 'すでにレビューが投稿されています'], 409);
        }

        // 画像がアップロードされた場合の処理
        $imageUrl = null; // 画像URLの初期値をnullに設定
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uniqueFileName = uniqid() . '.' . $image->getClientOriginalExtension(); // 一意のファイル名を生成
            $image->storeAs('review', $uniqueFileName, 'public'); // 保存先を指定して保存
            $imageUrl = $uniqueFileName; // ファイル名のみを設定
        }

        $review = Review::create([
            'user_id' => $userId,
            'shop_id' => $request->shop_id,
            'review_text' => $request->review_text,
            'rating' => $request->rating,
            'image_url' => $imageUrl,
        ]);

        return response()->json(['message' => 'レビューを投稿しました', 'review' => $review], 201);
    }


    // レビュー編集機能
    public function updateUserReview(ReviewRequest $request, $shopId)
    {
        $userId = Auth::id(); // ログイン中のユーザーID

        // レビューが存在するか確認
        $review = Review::where('shop_id', $shopId)->where('user_id', $userId)->first();

        if (!$review) {
            return response()->json(['message' => 'レビューが見つかりません'], 404);
        }

        // 画像がアップロードされた場合の処理
        $imageUrl = $review->image_url; // 初期値は現在の画像URL
        if ($request->hasFile('image')) {
            // 既存の画像を削除
            if ($imageUrl) {
                $existingImagePath = public_path('storage/review/' . basename($imageUrl));
                if (File::exists($existingImagePath)) {
                    File::delete($existingImagePath);
                }
            }

            $image = $request->file('image');
            $uniqueFileName = uniqid() . '.' . $image->getClientOriginalExtension(); // 一意のファイル名を生成
            $image->storeAs('review', $uniqueFileName, 'public'); // 保存先を指定して保存
            $imageUrl = $uniqueFileName; // 新しい画像のファイル名を設定
        }

        // レビューを更新
        $review->update([
            'review_text' => $request->review_text,
            'rating' => $request->rating,
            'image_url' => $imageUrl,
        ]);

        return response()->json(['message' => 'レビューが更新されました', 'review' => $review], 200);
    }


    // ユーザが自身で投稿したレビューを削除
    public function deleteUserReview($shopId)
    {
        $userId = Auth::id(); // ログイン中のユーザーID

        // ユーザーのレビューを取得
        $review = Review::where('shop_id', $shopId)->where('user_id', $userId)->first();

        if (!$review) {
            return response()->json(['message' => 'レビューが見つかりません'], 404);
        }

        // レビューに関連付けられた画像を削除
        if ($review->image_url) {
            $fileName = basename($review->image_url);
            $existingImagePath = public_path('storage/review/' . $fileName);

            if (File::exists($existingImagePath)) {
                File::delete($existingImagePath);
            }
        }

        // レビューを削除
        $review->delete();

        return response()->json(['message' => 'レビューが削除されました'], 200);
    }


    // adminユーザによるレビュー削除
    public function deleteReview($reviewId)
    {
        // レビューを取得
        $review = Review::where('id', $reviewId)->first();

        if (!$review) {
            return response()->json(['message' => 'レビューが見つかりません'], 404);
        }

        // レビューに関連付けられた画像を削除
        if ($review->image_url) {
            $fileName = basename($review->image_url);
            $existingImagePath = public_path('storage/review/' . $fileName);

            if (File::exists($existingImagePath)) {
                File::delete($existingImagePath);
            }
        }

        // レビューを削除
        $review->delete();

        return response()->json(['message' => 'レビューが削除されました'], 200);
    }
}
