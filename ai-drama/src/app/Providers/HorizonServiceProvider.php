<?php

namespace App\Providers;

use Laravel\Horizon\Horizon;
use Laravel\Horizon\HorizonApplicationServiceProvider;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();
        // 邮件通知配置（待 P1 完善）
        // Horizon::routeMailNotificationsTo('admin@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url');
    }

    protected function gate(): void
    {
        // 仅超级管理员可访问 Horizon
        $this->gate = function ($user) {
            return $user->hasRole('super_admin');
        };
    }
}
