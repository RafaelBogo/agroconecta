<?php $__env->startSection('title', 'Produtos'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
  
  <div class="search-card shadow-sm">
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-3">
      <div>
        <h2 class="mb-0">Produtos</h2>
        <small class="text-muted">Busque por nome e filtre por cidade</small>
      </div>
      <?php if(isset($products) && method_exists($products, 'count')): ?>
        <small class="text-muted"><?php echo e($products->count()); ?> resultado(s)</small>
      <?php endif; ?>
    </div>

    <form action="<?php echo e(route('products.search')); ?>" method="GET" class="row g-2">
      <div class="col-12 col-md-7">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-bag"></i></span>
          <input
            type="text"
            name="product"
            class="form-control"
            placeholder="Ex.: tomate, mel, queijo…"
            value="<?php echo e(request('product')); ?>"
            aria-label="Buscar por produto"
          >
          <button type="button" id="clearProduct" class="btn btn-outline-secondary d-none">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>

      <div class="col-12 col-md-3">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
          <select name="city" class="form-select" aria-label="Filtrar por cidade">
            <option value="">Todas as cidades</option>
            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($city); ?>" <?php echo e(request('city') == $city ? 'selected' : ''); ?>>
                <?php echo e($city); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>
      </div>

      <div class="col-12 col-md-2 d-grid">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-search"></i> Buscar
        </button>
      </div>
    </form>

    <?php if(request()->filled('product') || request()->filled('city')): ?>
      <div class="mt-2">
        <a href="<?php echo e(route('products.search')); ?>" class="btn btn-link p-0 text-decoration-none">
          Limpar filtros
        </a>
      </div>
    <?php endif; ?>
  </div>

  
  <div class="products-card shadow-sm mt-3">
    <?php if($products->isEmpty()): ?>
      <div class="text-center py-5">
        <div class="mb-3" style="font-size:42px;line-height:1"><i class="bi bi-box-seam"></i></div>
        <h5 class="mb-1">Nenhum produto encontrado</h5>
        <p class="text-muted mb-3">Tente ajustar sua busca ou selecionar outra cidade.</p>
        <a class="btn btn-dark" href="<?php echo e(route('products.show')); ?>"><i class="bi bi-arrow-counterclockwise"></i> Ver todos</a>
      </div>
    <?php else: ?>
      <div class="row g-3">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-12 col-sm-6 col-lg-4">
            <a href="<?php echo e(route('products.details', $product->id)); ?>" class="text-decoration-none text-reset">
              <div class="card product-card h-100">
                <div class="ratio ratio-16x9">
                  <img
                    src="<?php echo e(asset('storage/' . $product->photo)); ?>"
                    class="product-image rounded-top"
                    alt="<?php echo e($product->name); ?>"
                  >
                </div>
                <div class="card-body">
                  <h5 class="card-title mb-1 text-truncate"><?php echo e($product->name); ?></h5>
                  <p class="mb-1"><strong>Preço:</strong> R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?></p>
                  <small class="text-muted d-inline-flex align-items-center gap-1">
                    <i class="bi bi-geo-alt"></i> <?php echo e($product->city); ?>

                  </small>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <?php if(method_exists($products, 'links')): ?>
        <div class="d-flex justify-content-center mt-3">
          <?php echo e($products->links()); ?>

        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/products.showProducts.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/products.showProducts.css')); ?>">
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/products/showProducts.blade.php ENDPATH**/ ?>