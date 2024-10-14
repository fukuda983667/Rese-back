<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ログインしていて、role が 'user' か確認
        if (Auth::check() && Auth::user()->role === 'user') {
            return $next($request);
        }

        return response()->json(['message' => 'user権限がありません'], 403); // 権限エラー
    }
}
