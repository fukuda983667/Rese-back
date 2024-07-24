<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

//認証中の時だけ叩ける
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/users', [AuthController::class, 'getUsers'])->name('getUsers');
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        \Log::info($user);
        return $user;
    });
    Route::post('/posts', function (Request $request) {
        $users = User::all();
        return response()->json(compact('users'),200);
    });
});
