<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RBAC 权限校验中间件
 * 用法：Route::middleware('permission:users.read')
 * 权限点格式：module.action 例如 users.read / ai-keys.write
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'code' => 4001,
                'message' => '未登录',
                'data' => null,
            ], 401);
        }

        if ($user->isBanned()) {
            return response()->json([
                'code' => 4003,
                'message' => '账号已被封禁',
                'data' => null,
            ], 403);
        }

        // 超级管理员放行
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // 任一权限满足即可
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }

        return response()->json([
            'code' => 4003,
            'message' => '无操作权限：' . implode('|', $permissions),
            'data' => null,
        ], 403);
    }
}
