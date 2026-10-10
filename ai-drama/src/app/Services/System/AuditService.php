<?php

namespace App\Services\System;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * 审计服务 — 关键操作记录审计日志
 * 集成 owen-it/laravel-auditing，对 Eloquent 模型自动审计
 * 本服务用于补充非模型审计（如登录、支付回调、积分调整等关键动作）
 */
class AuditService
{
    // 记录自定义审计事件
    public function log(string $event, string $auditableType, $auditableId, array $oldValues = [], array $newValues = [], array $tags = []): void
    {
        $user = Auth::user();

        \App\Models\AuditLog::create([
            'user_id' => $user?->id,
            'user_type' => $user ? get_class($user) : null,
            'event' => $event,
            'auditable_type' => $auditableType,
            'auditable_id' => $auditableId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'tags' => implode(',', $tags),
        ]);
    }

    // 记录关键操作（支付/积分/封禁等）
    public function recordAction(string $action, string $description, array $context = []): void
    {
        $user = Auth::user();

        \App\Models\AuditLog::create([
            'user_id' => $user?->id,
            'user_type' => $user ? get_class($user) : null,
            'event' => $action,
            'auditable_type' => $context['auditable_type'] ?? 'System',
            'auditable_id' => $context['auditable_id'] ?? 0,
            'old_values' => $context['old'] ?? null,
            'new_values' => array_merge(['description' => $description], $context['new'] ?? []),
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'tags' => $context['tags'] ? implode(',', (array) $context['tags']) : null,
        ]);
    }
}
