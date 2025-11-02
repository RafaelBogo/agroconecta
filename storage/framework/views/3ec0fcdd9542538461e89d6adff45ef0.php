<?php $__env->startSection('title', 'Minha Conta'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
<div class="account-container">

  <div class="options-grid">
    <a href="<?php echo e(route('orders.index')); ?>" class="option-card" aria-label="Meus Pedidos">
      <span class="option-icon"><i class="bi bi-cart"></i></span>
      <h5>Meus Pedidos</h5>
      <p>Veja e acompanhe suas compras.</p>
    </a>

    <a href="<?php echo e(route('user.data')); ?>" class="option-card" aria-label="Meus Dados">
      <span class="option-icon"><i class="bi bi-person"></i></span>
      <h5>Meus Dados</h5>
      <p>Visualize e edite seus dados pessoais.</p>
    </a>

    <a href="<?php echo e(route('account.myProducts')); ?>" class="option-card" aria-label="Meus Produtos">
      <span class="option-icon"><i class="bi bi-box"></i></span>
      <h5>Meus Produtos</h5>
      <p>Gerencie os itens que você vende.</p>
    </a>

    <a href="<?php echo e(route('account.myRatings')); ?>" class="option-card" aria-label="Avaliações">
      <span class="option-icon"><i class="bi bi-star"></i></span>
      <h5>Avaliações</h5>
      <p>Veja e faça avaliações de compras.</p>
    </a>

    <a href="<?php echo e(route('seller.mySalesAnalysis')); ?>" class="option-card" aria-label="Suporte">
      <span class="option-icon"><i class="bi bi-bag"></i></span>
      <h5>Análise de Vendas</h5>
      <p>Visualize gráficos das suas vendas</p>
    </a>

    <a href="<?php echo e(route('support')); ?>" class="option-card" aria-label="Suporte">
      <span class="option-icon"><i class="bi bi-headset"></i></span>
      <h5>Suporte</h5>
      <p>Fale com a nossa equipe de ajuda.</p>
    </a>

  </div>

  <?php $__env->startSection('back', route('dashboard')); ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/minhaConta.css')); ?>">
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/myAccount.blade.php ENDPATH**/ ?>