<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PagoController;

// ===============================
// Rotas de Autenticação
// ===============================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===============================
// Rotas de Recuperação de Senha
// ===============================
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// ===============================
// Rota Inicial (Redireciona para login)
// ===============================
Route::get('/', function () {
    return redirect()->route('login');
});

// ===============================
// Rotas de Dashboard
// ===============================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

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

// ===============================
// Rotas de Produtos (vitrine)
// ===============================
Route::get('/produtos', [ProductController::class, 'showProducts'])->name('products.show');
Route::get('/produtos/buscar', [ProductController::class, 'search'])->name('products.search');
Route::get('/produtos/{id}', [ProductController::class, 'showProductDetails'])->name('products.details');

// ===============================
// Rotas de Venda
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/vender', fn () => redirect()->route('sell.cadastroProduto'))->name('sell.important');
    Route::get('/vender/cadastro', [ProductController::class, 'showCadastroProduto'])->name('sell.cadastroProduto');
    Route::post('/vender/cadastro', [ProductController::class, 'storeCadastroProduto'])->name('sell.store');
});

// ===============================
// Rotas de Minha Conta
// ===============================
Route::get('/minha-conta', [DashboardController::class, 'myAccount'])->name('myAccount');

// ===============================
// Rotas de Carrinho
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/delete', [CartController::class, 'deleteItem'])->name('cart.delete');
    Route::put('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::get('/cart/summary', [CartController::class, 'getCartSummary'])->name('cart.summary');
    Route::post('/cart/finalizar', [CartController::class, 'finalizarPedido'])->name('cart.finalizar');
    Route::post('/cart/checkout', [CartController::class, 'checkoutMP'])->name('cart.checkout');
});

// ===============================
// Rotas de Pedidos
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/account/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::put('/account/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/{order}/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/{order}/failure', [OrderController::class, 'failure'])->name('orders.failure');
    Route::get('/orders/{order}/pending', [OrderController::class, 'pending'])->name('orders.pending');
});

// ===============================
// Rotas de Usuário
// ===============================
Route::get('/meus-dados', [UserController::class, 'show'])->name('user.data');
Route::put('/meus-dados', [UserController::class, 'update'])->name('user.update');

// ===============================
// Rotas de Produtos do Usuário
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/account/my-products', [ProductController::class, 'myProducts'])->name('account.myProducts');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
});

// ===============================
// Rotas de Produtos do Usuário Para Inatividade ou Atividade
// ===============================
Route::post('/products/{id}/toggle-active', [ProductController::class, 'toggleActive'])
    ->middleware('auth')
    ->name('products.toggleActive');

// ===============================
// Rotas de Verificação de E-mail
// ===============================
Route::get('/email/verify', fn () => view('auth.verify'))->name('verify');
Route::post('/email/verify', [AuthController::class, 'verifyCode'])->name('verify.code');

// ===============================
// Rotas de Vendas (Vendedor)
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/minha-conta/minhas-vendas', [OrderController::class, 'mySales'])
        ->name('seller.mySales');

    Route::get('/minha-conta/analise-vendas', [OrderController::class, 'mySalesAnalysis'])
        ->name('seller.mySalesAnalysis');

    Route::post(
        '/minha-conta/minhas-vendas/confirmar-retirada',
        [OrderController::class, 'confirmRetirada']
    )->name('seller.confirmRetirada');
});


// ===============================
// Rotas de Suporte
// ===============================
Route::get('/suporte', function () {
    return view('account.support');
})->name('support');

// ===============================
// Rotas de Avaliações
// ===============================
Route::get('/account/myRatings', [ReviewController::class, 'index'])->name('account.myRatings')->middleware('auth');
Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('products.reviews.store')->middleware('auth');

// ===============================
// Rotas de Chat
// ===============================
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.inbox');
    Route::get('/chat/{userId}', [ChatController::class, 'showChat'])->name('chat.with');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::delete('/chat/{userId}/end', [ChatController::class, 'endConversation'])->name('chat.end');

    Route::get('/mensagens', [ChatController::class, 'index'])->name('messages');
});

// ===============================
// Mercado Pago
// ===============================
Route::post('/webhooks/mercadopago', [PagoController::class, 'webhook'])->name('mp.webhook');
Route::get('/pedido/{order}/sucesso',  [PagoController::class, 'success'])->name('orders.success');
Route::get('/pedido/{order}/falha',    [PagoController::class, 'failure'])->name('orders.failure');
Route::get('/pedido/{order}/pendente', [PagoController::class, 'pending'])->name('orders.pending');

Route::middleware('auth')->group(function () {
    Route::post('/pedido/{order}/refund', [PagoController::class, 'refund'])->name('orders.refund');
    Route::post('/pedido/{order}/cancel', [PagoController::class, 'cancel'])->name('orders.cancel');
});

//Para corrigir o erro das imagens na hospedagem
Route::get('/media/{path}', function ($path) {
    $full = storage_path('app/public/' . $path);

    if (!is_file($full)) {
        return response()->file(
            public_path('img/placeholder.png')
        );
    }
    return response()->file($full);
})->where('path', '.*')->name('media');
