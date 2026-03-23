<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('/login', 'login')->name('login');
    });

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::view('/dashboard', 'auth.dashboard', ['content' => 'dashboard'])->name('dashboard');
});
