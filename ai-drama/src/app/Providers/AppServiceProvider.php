<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 配置
    }

    public function boot(): void
    {
        // 注册 Inertia 共享数据（用户、权限等全局可见）
        Inertia::share([
            'app' => fn () => [
                'name' => config('app.name'),
                'env' => config('app.env'),
            ],
            'auth' => function () {
                $user = auth()->user();
                if (! $user) {
                    return null;
                }
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                    'phone' => $user->phone_masked,
                    'roles' => $user->roles->pluck('name')->toArray(),
                    'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                    'member_level' => $user->member_level,
                ];
            },
        ]);
    }
}
