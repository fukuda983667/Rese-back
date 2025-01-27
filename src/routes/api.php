<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\RegionController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// テスト用
Route::get('/helloworld', function () {
    return response()->json(['message' => 'Hello, World']);
});

// 一般ユーザー用の登録ルート
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// admin用の登録ルート
Route::post('/admin/register', [AuthController::class, 'registerAdmin'])->name('registerAdmin');


Route::group(['middleware' => ['auth:sanctum']], function () {
    // フロントのnuxt3でnuxt-auth-sanctumモジュールのメソッド使用すると勝手に/userを叩く
    Route::get('/user', [UserController::class, 'getUser'])->name('getUser');

    //個別店舗情報取得
    Route::get('/shops/{id}', [ShopController::class, 'detailShow'])->name('detailShow');
    Route::get('/reviews/shops/{id}', [ReviewController::class, 'getReviewsByShop'])->name('getReviewsByShop');
});


//認証中(ログイン中)かつ一般ユーザだけ叩ける
Route::group(['middleware' => ['auth:sanctum', 'verified', 'user']], function () {

    Route::get('/user/my-page', [UserController::class, 'getMyPage'])->name('getMyPage');

    //店舗情報一覧取得
    Route::get('/shops', [ShopController::class, 'getShops'])->name('getShops');


    // お気に入り店舗を追加する
    Route::post('/likes', [LikeController::class, 'toggleLike'])->name('toggleLike');

    // 予約登録
    Route::post('/reservations', [ReservationController::class, 'store'])->name('storeReservation');
    // 予約内容更新
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('updateReservation');
    // 予約削除
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('destroyReservation');

    // レビュー投稿
    Route::post('/reviews', [ReviewController::class, 'store'])->name('storeReview');
    Route::get('/reviews/shops/{id}/user', [ReviewController::class, 'getUserReview'])->name('getUserReview');
    Route::put('/reviews/shops/{id}/user', [ReviewController::class, 'updateUserReview'])->name('updateUserReview');
    Route::delete('/reviews/shops/{id}/user', [ReviewController::class, 'deleteUserReview'])->name('deleteUserReview');
});


//認証中(ログイン中)かつadminユーザだけ叩ける
Route::group(['middleware' => ['auth:sanctum', 'verified', 'admin']], function () {

    // vendor用の登録ルート
    Route::post('/vendor/register', [AuthController::class, 'registerVendor'])->name('registerVendor');
    // vendor一覧取得
    Route::get('/admin/vendors', [AdminController::class, 'getVendors'])->name('getVendors');
    // メール送信
    Route::post('/admin/send-email', [AdminController::class, 'sendEmail'])->name('sendEmail');

    // レビュー削除
    Route::delete('/admin/reviews/{id}', [ReviewController::class, 'deleteReview'])->name('deleteReview');
});


//認証中(ログイン中)かつvendorユーザだけ叩ける
Route::group(['middleware' => ['auth:sanctum', 'verified', 'vendor']], function () {
    // ベンダーが自分の店舗情報一覧を取得する
    Route::get('/vendor/shops', [VendorController::class, 'getMyShops'])->name('getMyShops');
    //ベンダーが自分の特定の店舗情報を取得する
    Route::get('/vendor/shops/{id}', [ShopController::class, 'getMyShop'])->name('getMyShop');
    //特定の店舗の予約情報を日付で絞って取得する。デフォルトの日付は今日
    Route::get('/vendor/shops/{shop_id}/reservations', [ReservationController::class, 'getReservationsByShop'])->name('getReservationsByShop');

    // 新規店舗作成
    Route::post('/vendor/shop/create', [ShopController::class, 'store'])->name('storeShop');
    // 店舗情報更新
    Route::put('/vendor/shop/update/{id}', [ShopController::class, 'update'])->name('updateShop');

    // 新規店舗作成時にgenresテーブルとregionsテーブルを取得する
    Route::get('/genres', [GenreController::class, 'getGenres'])->name('sgetGenres');
    Route::get('/regions', [RegionController::class, 'getRegions'])->name('getRegions');
});