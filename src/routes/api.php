<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register');


//認証中(ログイン中)の時だけ叩ける
Route::group(['middleware' => 'auth:sanctum'], function () {

    // フロントのnuxt3でnuxt-auth-sanctumモジュールのメソッド使用すると勝手に/userを叩く
    Route::get('/user', [UserController::class, 'getUser'])->name('getUser');
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
