<?php $__env->startSection('title', 'Meus Produtos'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
    <h2>Meus Produtos</h2>

    <?php if($products->isEmpty()): ?>
      <div class="empty-state text-center py-5">
          <img src="<?php echo e(asset('images/empty-box.svg')); ?>" alt="" class="empty-illust mb-3">
          <h5 class="mb-1">Você ainda não cadastrou produtos</h5>
          <p class="text-muted mb-3">Que tal começar agora?</p>
        <a href="<?php echo e(route('sell.store')); ?>" class="btn btn-success">
          <i class="bi bi-plus-lg me-1"></i> Cadastrar produto
        </a>
      </div>
    <?php else: ?>
      <div class="header-row d-flex align-items-center justify-content-between mb-3">
         <h2 class="m-0">Produtos em Venda: <span class="count">(<?php echo e($products->count()); ?>)</span></h2>
        <a href="<?php echo e(route('sell.store')); ?>" class="btn btn-success">
          <i class="bi bi-plus-lg me-1"></i> Novo
         </a>
      </div>

  <div class="products-grid">
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="product-card">
        <div class="thumb">
          <img loading="lazy" src="<?php echo e(asset('storage/' . $product->photo)); ?>" alt="<?php echo e($product->name); ?>">
      </div>

    <div class="middle">
      <h5 class="name m-0">
        <?php echo e($product->name); ?>

        <?php if (! ($product->is_active)): ?>
          <span class="badge bg-secondary ms-2">Inativo</span>
        <?php endif; ?>
      </h5>


    <div class="meta">
      <?php if(!is_null($product->stock)): ?>
        <span class="badge rounded-pill bg-light text-dark">
          <i class="bi bi-box-seam me-1"></i> <?php echo e($product->stock); ?> em estoque
        </span>
      <?php endif; ?>
    </div>
  </div>

  <div class="actions">
    <div class="price">R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?>

      <?php if(!empty($product->unit)): ?> <small class="text-muted">/ <?php echo e($product->unit); ?></small><?php endif; ?>
    </div>

    <div class="btns">
      <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-pencil-square me-1"></i> Editar
      </a>

      <form action="<?php echo e(route('products.toggleActive', $product->id)); ?>" method="POST" class="d-inline">
        <?php echo csrf_field(); ?>
          <button class="btn btn-sm btn-<?php echo e($product->is_active ? 'warning' : 'success'); ?>">
        <?php if($product->is_active): ?>
          <i class="bi bi-pause-circle me-1"></i> Inativar
        <?php else: ?>
          <i class="bi bi-play-circle me-1"></i> Ativar
        <?php endif; ?>
        </button>
      </form>


    </div>
  </div>
</div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
<?php endif; ?>

    <a href="<?php echo e(route('minha.conta')); ?>" class="btn-voltar">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/account.myProducts.css')); ?>">
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/myProducts.blade.php ENDPATH**/ ?>