<?php

use App\Http\Controllers\Auth\SessionLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [SessionLoginController::class, 'create'])->name('login');
    Route::post('/login', [SessionLoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [SessionLoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', fn () => redirect()->route('login'))->name('dashboard'); 
});