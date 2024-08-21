<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // ユーザー登録機能
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
        ]);

        return response()->json(['message' => 'ユーザー登録成功'],201);
    }


    //ログイン機能
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        //該当のカラムがusrテーブルに存在していた場合、trueかfalseで返る。
        if (Auth::attempt($credentials)) {
            // $request->session()->regenerate();
            //ステータスコードを返さないとフロント側が認証成功したか失敗したかを判断できない
            return response()->json(['message' => 'ログイン成功'], 200);
        }

        return response()->json(['message' => 'ログイン失敗'], 401); // 追加
    }


    //ログアウト機能
    public function logout(Request $request) {
        Auth::logout(); // ユーザーのログアウト
        $request->session()->invalidate(); // セッションの無効化
        $request->session()->regenerateToken(); // CSRFトークンの再生成

        return response()->json(['message' => 'ログアウト成功'], 200);
    }
}
