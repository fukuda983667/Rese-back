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

// 認証メール再送信用　実装してない
Route::post('/email/resend', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => '既にメール認証済みです。'], 400);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => '確認メールを再送信しました。'], 200);
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');


Route::group(['middleware' => ['auth:sanctum']], function () {
    // フロントのnuxt3でnuxt-auth-sanctumモジュールのメソッド使用すると勝手に/userを叩く
    Route::get('/user', [UserController::class, 'getUser'])->name('getUser');
});


//認証中(ログイン中)かつ一般ユーザだけ叩ける
Route::group(['middleware' => ['auth:sanctum', 'verified', 'user']], function () {

    // エンドポイントを/usersにして/users/{id}みたいにした方がいい？
    Route::get('/user/my-page', [UserController::class, 'getMyPage'])->name('getMyPage');

    //店舗情報一覧取得
    Route::get('/shops', [ShopController::class, 'getShops'])->name('getShops');
    //個別店舗情報取得
    Route::get('/shops/{id}', [ShopController::class, 'detailShow'])->name('detailShow');

    // お気に入り店舗を追加する
    Route::post('/likes', [LikeController::class, 'toggleLike'])->name('toggleLike');

    // 予約登録
    Route::post('/reservations', [ReservationController::class, 'store'])->name('storeReservation');
    // 予約内容更新
    Route::put('/reservations/{id}', [ReservationController::class, 'update'])->name('updateReservation');
    // 予約削除 なるべく{id}で実装した方がいい気がする。
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('destroyReservation');

    // レビュー投稿
    Route::post('/reviews', [ReviewController::class, 'store'])->name('storeReview');
    Route::get('/reviews/shops/{id}', [ReviewController::class, 'getReviewsByShop'])->name('getReviewsByShop');
});


//認証中(ログイン中)かつadminユーザだけ叩ける
Route::group(['middleware' => ['auth:sanctum', 'verified', 'admin']], function () {

    // vendor用の登録ルート
    Route::post('/vendor/register', [AuthController::class, 'registerVendor'])->name('registerVendor');
    // vendor一覧取得
    Route::get('/admin/vendors', [AdminController::class, 'getVendors'])->name('getVendors');
    // メール送信
    Route::post('/admin/send-email', [AdminController::class, 'sendEmail'])->name('sendEmail');
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
});