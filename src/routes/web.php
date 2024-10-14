<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    return view('test');
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // メール確認を完了

    // ユーザーのロールに応じたURLにリダイレクト
    $role = $request->user()->role;

    if ($role === 'admin') {
        return redirect('http://localhost:3000/admin/login');
    } elseif ($role === 'vendor') {
        return redirect('http://localhost:3000/vendor/login');
    } else {
        return redirect('http://localhost:3000/login');
    }
})->middleware(['auth', 'signed'])->name('verification.verify');