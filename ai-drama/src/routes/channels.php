<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast 频道 — WebSocket 私有频道
|--------------------------------------------------------------------------
*/

// 用户私有频道（任务进度、积分变动、支付通知）
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// 项目频道（项目相关任务进度）
Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    // M5 实现：校验用户是否拥有该项目
    return true;
});

// 画布频道
Broadcast::channel('canvas.{canvasId}', function ($user, $canvasId) {
    return true;
});
