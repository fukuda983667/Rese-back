<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class AuthController extends Controller
{
    // 一般ユーザー登録機能
    public function register(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'user', // デフォルトで'user'とする
        ]);

        // ユーザーをログインさせる
        // 認証メールのリンクを踏むときにログイン状態にしておく必要がある。
        Auth::login($user);

        // メール認証リンクの送信
        event(new Registered($user));

        return response()->json(['message' => 'ユーザー登録成功'],201);
    }


    // vendor用の登録機能
    public function registerVendor(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $vendor = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'vendor', // vendorとして登録
            'email_verified_at' => now(), // 現在の時刻をセット
        ]);

        return response()->json(['message' => 'ベンダー登録成功'], 201);
    }


    // admin用の登録機能
    public function registerAdmin(Request $request) {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $admin = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'admin', // adminとして登録
        ]);

        // ユーザーをログインさせる
        // 認証メールのリンクを踏むときにログイン状態にしておく必要がある。
        Auth::login($user);

        // メール認証リンクの送信
        event(new Registered($user));

        return response()->json(['message' => '管理者登録成功'], 201);
    }


    //ユーザーログイン機能
    public function login(Request $request) {
        // リクエストに含まれるデータを検証
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:user,vendor,admin'], // roleの検証
        ]);

        // 該当のカラムがユーザーテーブルに存在していた場合
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $user = Auth::user();

            // ロールがリクエストされたものと一致するか確認
            if ($user->role !== $credentials['role']) {
                Auth::logout(); // ロールが一致しない場合、即ログアウト
                return response()->json(['message' => '権限が一致しません'], 403); // 403 Forbidden
            }

            // ログイン成功
            return response()->json(['message' => 'ログイン成功'], 200);
        }

        // 認証失敗
        return response()->json(['message' => 'ログイン失敗'], 401);
    }


    //ログアウト機能
    public function logout(Request $request) {
        Auth::logout(); // ユーザーのログアウト
        $request->session()->invalidate(); // セッションの無効化
        $request->session()->regenerateToken(); // CSRFトークンの再生成

        return response()->json(['message' => 'ログアウト成功'], 200);
    }
}
