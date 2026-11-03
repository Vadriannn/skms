<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data-entry', [DashboardController::class, 'dataEntry'])->name('dashboard.data_entry');

    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
    Route::get('/faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::post('/faqs/check-similarity', [FaqController::class, 'checkSimilarity'])->name('faqs.check_similarity');
    Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');
    Route::get('/faqs/history', [FaqController::class, 'history'])->name('faqs.history');

    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.update');

    // ─── VALIDATOR ROUTES ───────────────────────────────────────────────────────
    Route::middleware('validator.only')->group(function () {
        Route::get('/validator/dashboard', [ValidatorController::class, 'dashboard'])->name('validator.dashboard');
        Route::get('/validator/antrian', [ValidatorController::class, 'antrian'])->name('validator.antrian');
        Route::get('/validator/disetujui', [ValidatorController::class, 'disetujui'])->name('validator.disetujui');
        Route::get('/validator/semua-faq', [ValidatorController::class, 'semuaFaq'])->name('validator.semua_faq');
        Route::get('/validator/faq/{faq}', [ValidatorController::class, 'show'])->name('validator.faq.show');
        Route::post('/validator/faq/{faq}/approve', [ValidatorController::class, 'approve'])->name('validator.faq.approve');
        Route::post('/validator/faq/{faq}/reject', [ValidatorController::class, 'reject'])->name('validator.faq.reject');
    });
});

