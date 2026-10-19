<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Halaman Utama
Route::get('/', function () {
    return redirect()->route('categories.index');
});

// Autentikasi (Login & Logout)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang Membutuhkan Login
Route::middleware('auth')->group(function () {
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
});
