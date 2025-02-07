<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

// ==================================
// Rotas de Autenticação
// ==================================
Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->name('verify');
    Route::post('/email/verify', [AuthController::class, 'verifyCode'])->name('verify.code');
});

// ==================================
// Rotas de Recuperação de Senha
// ==================================
Route::prefix('password')->group(function () {
    Route::get('/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// ==================================
// Rotas de Dashboard
// ==================================
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/search', function (Illuminate\Http\Request $request) {
        $query = $request->only(['product', 'city']);
        return redirect()->route('products.show', $query);
    })->name('dashboard.search');
});

// ==================================
// Rotas para Produtos
// ==================================
Route::prefix('produtos')->group(function () {
    Route::get('/', [ProductController::class, 'showProducts'])->name('products.show');
    Route::get('/buscar', [ProductController::class, 'search'])->name('products.search');
    Route::get('/{id}', [ProductController::class, 'showProductDetails'])->name('products.details');
});

// ==================================
// Rotas de Venda
// ==================================
Route::prefix('vender')->middleware('auth')->group(function () {
    Route::get('/', [ProductController::class, 'showImportant'])->name('sell.important');
    Route::get('/cadastro', [ProductController::class, 'showCadastroProduto'])->name('sell.cadastroProduto');
    Route::post('/cadastro', [ProductController::class, 'storeCadastroProduto'])->name('sell.store');
    Route::get('/concluido', function () {
        return view('sell.complete');
    })->name('sell.complete');
});

// ==================================
// Rotas de Carrinho
// ==================================
Route::prefix('cart')->middleware('auth')->group(function () {
    Route::get('/', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/delete', [CartController::class, 'deleteItem'])->name('cart.delete');
    Route::put('/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::get('/summary', [CartController::class, 'getCartSummary'])->name('cart.summary');
    Route::post('/finalizar', [CartController::class, 'finalizarPedido'])->name('cart.finalizar');
});

// ==================================
// Rotas de Pedidos
// ==================================
Route::prefix('account/orders')->middleware('auth')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::put('/{order}', [OrderController::class, 'update'])->name('orders.update');
});

// ==================================
// Rotas de Conta do Usuário
// ==================================
Route::prefix('minha-conta')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'minhaConta'])->name('minha.conta');
    Route::get('/meus-dados', [UserController::class, 'show'])->name('user.data');
    Route::put('/meus-dados', [UserController::class, 'update'])->name('user.update');
    Route::get('/minhas-vendas', [OrderController::class, 'mySales'])->name('seller.mySales');
    Route::post('/minhas-vendas/confirmar-retirada', [OrderController::class, 'confirmRetirada'])->name('seller.confirmRetirada');
});

// ==================================
// Rotas de Meus Produtos
// ==================================
Route::prefix('account/my-products')->middleware('auth')->group(function () {
    Route::get('/', [ProductController::class, 'myProducts'])->name('account.myProducts');
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{id}', [ProductController::class, 'update'])->name('products.update');
});
