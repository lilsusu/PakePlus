<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| 后台管理端路由
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Admin/Dashboard');
})->name('admin.dashboard');

// 用户管理（M2+ 实现具体 API）
Route::prefix('users')->group(function () {
    Route::get('/', fn () => Inertia::render('Admin/Users/Index'))->name('admin.users.index');
    Route::get('/{id}', fn ($id) => Inertia::render('Admin/Users/Show', ['id' => $id]))->name('admin.users.show');
});

// RBAC
Route::prefix('rbac')->group(function () {
    Route::get('/roles', fn () => Inertia::render('Admin/Rbac/Roles'))->name('admin.rbac.roles');
    Route::get('/permissions', fn () => Inertia::render('Admin/Rbac/Permissions'))->name('admin.rbac.permissions');
});

// 系统配置
Route::get('/system/audit', fn () => Inertia::render('Admin/System/Audit'))->name('admin.system.audit');
