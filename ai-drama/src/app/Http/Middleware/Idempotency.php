<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * 幂等中间件 — 写操作幂等
 * 用法：Route::middleware('idempotent')
 * 客户端需携带 Idempotency-Key 头；不携带时自动生成（仅限非幂等场景）
 * 同一 Idempotency-Key + 用户 + 路由 在 24 小时内重复请求返回首次结果
 */
class Idempotency
{
    public function handle(Request $request, Closure $next, bool $autoGenerate = false): Response
    {
        // 仅对写操作生效
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $next($request);
        }

        $key = $request->header('Idempotency-Key');
        if (! $key) {
            if ($autoGenerate) {
                $key = Str::uuid()->toString();
            } else {
                return $next($request); // 不强制时直接放行
            }
        }

        $user = $request->user();
        $userId = $user?->id ?: 'guest';
        $cacheKey = "idem:{$userId}:{$request->method()}:{$request->path()}:{$key}";

        // 已处理过，返回缓存结果
        if (Cache::has($cacheKey . ':result')) {
            $cached = Cache::get($cacheKey . ':result');
            return response()->json($cached['body'], $cached['status'])
                ->withHeaders($cached['headers'] ?? []);
        }

        // 标记处理中（防并发）
        if (Cache::has($cacheKey . ':processing')) {
            return response()->json([
                'code' => 4091,
                'message' => '请求正在处理中，请勿重复提交',
                'data' => null,
            ], 409);
        }

        Cache::put($cacheKey . ':processing', true, 60);

        $response = $next($request);

        // 仅缓存成功响应
        if ($response->getStatusCode() < 400) {
            $body = json_decode($response->getContent(), true);
            Cache::put($cacheKey . ':result', [
                'body' => $body,
                'status' => $response->getStatusCode(),
                'headers' => [],
            ], 86400); // 24 小时
        }

        Cache::forget($cacheKey . ':processing');

        return $response;
    }
}
