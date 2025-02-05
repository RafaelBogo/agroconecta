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
Route::get('/vender', [ProductController::class, 'showImportant'])->name('sell.important');
Route::get('/vender/cadastro', [ProductController::class, 'showCadastroProduto'])
    ->name('sell.cadastroProduto')
    ->middleware('auth');
Route::post('/vender/cadastro', [ProductController::class, 'storeCadastroProduto'])
    ->name('sell.store')
    ->middleware('auth');
Route::get('/vender/concluido', function () {
    return view('sell.complete');
})->name('sell.complete');

// Rotas para produtos
Route::get('/produtos', [ProductController::class, 'showProducts'])->name('products.show');
Route::get('/produtos/buscar', [ProductController::class, 'search'])->name('products.search');

// Rota para a Minha Conta
Route::get('/minha-conta', [DashboardController::class, 'minhaConta'])->name('minha.conta');

// Exibe o produto
Route::get('/produtos/{id}', [ProductController::class, 'showProductDetails'])->name('products.details');

Route::middleware('auth')->group(function () {
    // Visualizar o carrinho
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');

    // Adicionar ao carrinho
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');

    // Remover do carrinho
    Route::delete('/cart/delete', [CartController::class, 'deleteItem'])->name('cart.delete');

    // Atualizar quantidade no carrinho
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');

    // Resumo do carrinho
    Route::get('/cart/summary', [CartController::class, 'getCartSummary'])->name('cart.summary');

    // Finalizar o pedido
    Route::post('/cart/finalizar', [CartController::class, 'finalizarPedido'])->name('cart.finalizar');
});

//Rotas para orders

Route::middleware(['auth'])->group(function () {
    Route::get('/account/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::put('/account/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
});

//Rota para Meus Dados
Route::get('/meus-dados', action: [UserController::class, 'show'])->name('user.data');
Route::put('/meus-dados', [UserController::class, 'update'])->name('user.update');

// Rotas para Meus Produtos
Route::middleware(['auth'])->group(function () {
    Route::get('/account/my-products', [ProductController::class, 'myProducts'])->name('account.myProducts');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit'); // Adicione esta rota
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update'); // Para salvar as edições
});

Route::get('/email/verify', function () {
    return view('auth.verify'); // Certifique-se de que o arquivo 'verify.blade.php' está em 'resources/views/auth'
})->name('verify');

Route::post('/email/verify', [AuthController::class, 'verifyCode'])->name('verify.code');
