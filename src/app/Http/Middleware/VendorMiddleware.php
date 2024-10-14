<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ログインしていて、role が 'vendor' か確認
        if (Auth::check() && Auth::user()->role === 'vendor') {
            return $next($request);
        }

        return response()->json(['message' => 'vendor権限がありません'], 403);
    }
}