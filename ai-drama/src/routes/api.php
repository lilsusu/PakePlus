<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API 路由 — 用户端 + Webhook
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    Route::post('sms/send', [AuthController::class, 'sendSms']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login/phone', [AuthController::class, 'loginByPhone']);
    Route::post('login/email', [AuthController::class, 'loginByEmail']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// 后续模块路由将在此加载
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('wallet', ...);
//     ...
// });

// Webhook 路由（无需认证，但需内部签名校验）
Route::post('/webhook/ai-callback', function () {
    // M3 实现
})->name('webhook.ai-callback');

Route::post('/payments/callback/{channel}', function ($channel) {
    // M2 实现
})->name('payments.callback');
