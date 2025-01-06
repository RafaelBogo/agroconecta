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

// Rotas para recuperação de senha
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Rota para dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Rota de busca no dashboard
Route::get('/dashboard/search', function (Illuminate\Http\Request $request) {
    $query = [];
    if ($request->filled('product')) {
        $query['product'] = $request->product;
    }
    if ($request->filled('city')) {
        $query['city'] = $request->city;
    }
    return redirect()->route('products.show', $query);
})->name('dashboard.search');

// Rota para logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rotas do processo de venda
Route::get('/vender', [ProductController::class, 'showImportant'])->name('sell.important'); // Página de dicas
Route::get('/vender/cadastro', [ProductController::class, 'showCadastroProduto'])
    ->name('sell.cadastroProduto')
    ->middleware('auth'); // Garante que o usuário está logado
Route::post('/vender/cadastro', [ProductController::class, 'storeCadastroProduto'])
    ->name('sell.store')
    ->middleware('auth'); // Garante que o usuário está logado

// Rotas para produtos
Route::get('/produtos', [ProductController::class, 'showProducts'])->name('products.show');
Route::get('/produtos/buscar', [ProductController::class, 'search'])->name('products.search');

// Rota para a Minha Conta
Route::get('/minha-conta', [DashboardController::class, 'minhaConta'])->name('minha.conta');
