<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Rota para exibir o formulário de login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rota para exibir o formulário de registro
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// Rota para processar o registro
Route::post('/register', [AuthController::class, 'register']);

// Rota para exibir o formulário de verificação de código
Route::get('/verify', [AuthController::class, 'showVerificationForm'])->name('verify');
// Rota para processar o código de verificação
Route::post('/verify', [AuthController::class, 'verifyCode']);

// Rota para página de recuperação de senha (ainda em construção)
Route::get('/password/reset', function () {
    return 'Página de recuperação de senha em construção.';
})->name('password.request');

