<?php

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', function () {
    return 'Página de registro em construção.';
})->name('register');
Route::get('/password/reset', function () {
    return 'Página de recuperação de senha em construção.';
})->name('password.request');
