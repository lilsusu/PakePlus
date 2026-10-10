<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web 路由 — 用户端前端
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/login', fn () => Inertia::render('Auth/Login'))->name('login');
Route::get('/register', fn () => Inertia::render('Auth/Register'))->name('register');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('User/Dashboard'))->name('dashboard');
});
