<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;



// Rota para exibir o formulário de login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Rota para exibir o formulário de registro
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rota para exibir o formulário de verificação de código
Route::get('/verify', [AuthController::class, 'showVerificationForm'])->name('verify');
Route::post('/verify', [AuthController::class, 'verifyCode']);

// Rotas para recuperação de senha
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Rota para dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

//Rota para Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//Rotas do Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::post('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');

//Rota paga a Minha conta
Route::get('/minha-conta', [DashboardController::class, 'minhaConta'])->name('minha.conta');

// Rotas para cadastro de produtos (Vender)
Route::get('/vender', [ProductController::class, 'showStep1'])->name('sell.step1'); // Página inicial para venda
Route::post('/vender', [ProductController::class, 'storeStep1'])->name('sell.step1.save');

Route::get('/vender/step2', [ProductController::class, 'showStep2'])->name('sell.step2');
Route::post('/vender/step2', [ProductController::class, 'storeStep2'])->name('sell.step2.save');

Route::get('/vender/step3', [ProductController::class, 'showStep3'])->name('sell.step3');
Route::post('/vender/step3', [ProductController::class, 'storeStep3'])->name('sell.step3.save');

Route::get('/vender/complete', [ProductController::class, 'showComplete'])->name('sell.complete');
