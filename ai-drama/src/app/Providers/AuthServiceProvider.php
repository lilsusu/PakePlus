<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Gate: 使用 spatie permission 进行权限校验
        // 权限点格式：module.action 例如 users.read, ai-keys.write
        // 在中间件中使用 permission:users.read

        Gate::before(function ($user, $ability) {
            // 超级管理员拥有全部权限
            if ($user->hasRole('super_admin')) {
                return true;
            }
            // 否则由 spatie permission 判断
            return $user->hasPermissionTo($ability, 'web') ?: null;
        });
    }
}
