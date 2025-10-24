<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>AgroConecta - <?php echo $__env->yieldContent('title', 'Página'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <style>
    body {
        background-image: url('<?php echo e(asset("images/background2.jpg")); ?>');
        background-size: cover;
        background-position: center;
        font-family: 'Arial', sans-serif;
        min-height: 100vh;
        margin: 0;
        padding-top: 70px;
    }

    .navbar {
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1030;
        background-color: rgba(120, 123, 123, 0.9);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        opacity: 0.9;
    }

    .navbar a {
        color: white;
        text-decoration: none;
    }

    .navbar a:hover {
        text-decoration: underline;
        color: #ccc;
    }

    .content-box {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(5px);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
        max-width: 1200px;
        width: 100%;
        margin: 50px auto;
        max-height: 80vh;
        overflow-y: auto;
    }

    .content-box::-webkit-scrollbar {
        width: 35px;
    }

    .content-box::-webkit-scrollbar-track {
        background: rgba(245, 245, 245, 0.9);
        border-radius: 20px;
    }

    .content-box::-webkit-scrollbar-thumb {
        background-color: rgba(120, 120, 120, 0.6);
        border-radius: 20px;
    }

    .content-box::-webkit-scrollbar-thumb:hover {
        background-color: rgba(100, 100, 100, 0.9);
    }

    .btn-voltar{
        background: #fff;
        color: #111827;
        border: 1px solid rgba(0,0,0,.12);
        border-radius: 10px;
        padding: 8px 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background .15s ease, color .15s ease, transform .15s ease, box-shadow .15s ease, border-color .15s ease;
    }
    .btn-voltar:hover{
        background: rgba(25,135,84,.10);   /* verde translúcido */
        color: #198754;                    /* texto verde */
        border-color: rgba(25,135,84,.30);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(17,24,39,.08);
        text-decoration: none;
    }
    .btn-voltar:focus-visible{
        outline: 2px solid rgba(25,135,84,.35);
        outline-offset: 2px;
    }
    .btn-voltar .bi{ font-size: 1rem; }</style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">AgroConecta</a>
            <div class="navbar-nav mx-auto">
                <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Início</a>
                <a class="nav-link" href="<?php echo e(route('products.show')); ?>">Produtos</a>
                <a class="nav-link" href="<?php echo e(route('sell.cadastroProduto')); ?>">Vender</a>
                <a class="nav-link" href="<?php echo e(route('chat.inbox')); ?>">Mensagens</a>

                <a class="nav-link" href="<?php echo e(route('cart.view')); ?>">Carrinho</a>
            </div>
            <div class="d-flex align-items-center">
                <a class="nav-link px-3" href="<?php echo e(route('minha.conta')); ?>">Minha Conta</a>
                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                    <?php echo csrf_field(); ?>
                    <a href="#" class="nav-link text-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (! empty(trim($__env->yieldContent('boxed')))): ?>
            <div class="content-box">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        <?php else: ?>
            <?php echo $__env->yieldContent('content'); ?>
        <?php endif; ?>
    </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\layouts\app.blade.php ENDPATH**/ ?>