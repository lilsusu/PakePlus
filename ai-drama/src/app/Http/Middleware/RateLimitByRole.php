<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/**
 * 按角色限流中间件
 * 用法：Route::middleware('rate.role:60,1') 表示每分钟 60 次
 */
class RateLimitByRole
{
    public function handle(Request $request, Closure $next, int $maxAttempts = 60, int $decayMinutes = 1): Response
    {
        $user = $request->user();
        if (! $user) {
            return $next($request);
        }

        // 会员放宽限流
        if ($user->member_level > 0) {
            $maxAttempts = $maxAttempts * 3;
        }

        $key = 'rl:' . $user->id . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'code' => 4291,
                'message' => '请求过于频繁，请稍后再试',
                'data' => null,
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }
}
