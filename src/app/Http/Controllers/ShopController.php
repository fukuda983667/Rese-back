<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Review;
use App\Models\Genre;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    // vendorユーザが新規店舗を作成
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'name' => 'required|string|max:50',
            'region_id' => 'required|integer|exists:regions,id',
            'genre_id' => 'required|integer|exists:genres,id',
            'description' => 'required|string|max:400',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        // 画像ファイルの保存処理
        $imagePath = null;
        if ($request->hasFile('image')) {
            // 'shop' ディレクトリに保存し、パスを取得
            $storedImagePath = $request->file('image')->store('shop', 'public');
            $imagePath = basename($storedImagePath); // ファイル名のみを取得
        }

        // 店舗データの作成
        $shop = new Shop();
        $shop->name = $request->name;
        $shop->region_id = $request->region_id;
        $shop->genre_id = $request->genre_id;
        $shop->description = $request->description;
        $shop->user_id = Auth::id(); // 現在の認証ユーザーを設定
        $shop->image_url = $imagePath;
        $shop->save();

        return response()->json(['message' => '店舗が正常に作成されました'], 201);
    }


    //店舗情報一覧取得
    public function getShops()
    {
        // 現在認証中のユーザーを取得
        $user = Auth::user();
        // ユーザーがLikeしている店舗のIDを取得
        $likedShops = $user->likes->pluck('shop_id')->toArray();
        $baseUrl = Config::get('app.url') . '/storage/shop/'; //envのAPP_URLを利用して店舗画像を保存しているディレクトリのパスを設定

        // 全店舗の情報を取得し、各店舗にisLikedプロパティを追加
        $shops = Shop::with(['genre', 'region'])->get()->map(function ($shop) use ($likedShops, $baseUrl) {
            $shop->isLiked = in_array($shop->id, $likedShops);
            $shop->image_url = $baseUrl . $shop->image_url; //店舗画像をフルパスにして格納

            // レビューの平均評価を計算し、小数点第二位で切り捨て
            $averageRating = Review::where('shop_id', $shop->id)
                                    ->avg('rating');
            $shop->rating = floor($averageRating * 10) / 10;

            // genreとregionのnameを追加
            $shop->genre_name = $shop->genre ? $shop->genre->name : null;
            $shop->region_name = $shop->region ? $shop->region->name : null;

            return $shop;
        });

        // genres テーブルからのデータ取得
        $genres = Genre::pluck('name')->unique()->values()->all();

        // regions テーブルからのデータ取得
        $regions = Region::pluck('name')->unique()->values()->all();

        // レスポンスをJSON形式で返却
        return response()->json(compact('shops', 'genres', 'regions'), 200);
    }


    //一般userが店舗の詳細情報を取得
    public function detailShow($id)
    {
        $user = Auth::user();

        // 店舗の詳細情報を genre と region の情報と一緒に取得
        $shop = Shop::with(['genre', 'region'])->find($id);

        if ($shop) {
            // 店舗画像のフルパスを設定
            $shop->image_url = Config::get('app.url') . '/storage/shop/' . $shop->image_url;

            // genre_name と region_name カラムを追加
            $shop->genre_name = $shop->genre ? $shop->genre->name : null;
            $shop->region_name = $shop->region ? $shop->region->name : null;

            // 現在のユーザーがその店舗をお気に入り登録しているか確認
            $shop->isLiked = $user ? $user->likes->pluck('shop_id')->contains($shop->id) : false;

            // 店舗のレビュー平均評価を計算（小数点第二位で切り捨て）
            $averageRating = Review::where('shop_id', $shop->id)
                                    ->avg('rating');
            $shop->rating = $averageRating ? floor($averageRating * 10) / 10 : null;

            return response()->json(compact('shop'), 200);
        } else {
            return response()->json(['message' => 'Shop not found'], 404);
        }
    }


    //vendorユーザが運営する店舗の情報取得
    public function getMyShop($id)
    {
        $user = Auth::user();
        $shop = Shop::find($id);

        if ($shop) {
            $shop->image_url = Config::get('app.url') . '/storage/shop/' . $shop->image_url;

            // genres テーブルからのデータ取得 (id と name)
            $genres = Genre::select('id', 'name')->get();
            // regions テーブルからのデータ取得 (id と name)
            $regions = Region::select('id', 'name')->get();

            return response()->json(compact('shop', 'genres', 'regions'),200);
        } else {
            return response()->json(['message' => 'Shop not found'], 404);
        }
    }


    // 店舗情報更新
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'id' => 'required|integer|exists:shops,id',
            'name' => 'required|string|max:50',
            'region_id' => 'required|integer|exists:regions,id',
            'genre_id' => 'required|integer|exists:genres,id',
            'description' => 'required|string|max:400',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
        ]);

        // 店舗を取得
        $shop = Shop::find($id);

        if (!$shop || $shop->user_id !== Auth::id()) {
            return response()->json(['message' => '店舗が見つかりません'], 404);
        }

        // 画像ファイルの保存処理
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($shop->image_url) {
                Storage::disk('public')->delete('shop/' . $shop->image_url);
            }

            // 新しい画像を保存
            $storedImagePath = $request->file('image')->store('shop', 'public');
            $shop->image_url = basename($storedImagePath); // ファイル名のみを取得
        }

        // 店舗情報の更新
        $shop->name = $request->name;
        $shop->region_id = $request->region_id;
        $shop->genre_id = $request->genre_id;
        $shop->description = $request->description;

        $shop->save();

        return response()->json(['message' => '店舗情報が正常に更新されました'], 200);
    }
}
