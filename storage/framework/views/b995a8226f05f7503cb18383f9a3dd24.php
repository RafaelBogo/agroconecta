<?php $__env->startSection('title', 'Minhas Avaliações'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <h3 class="mb-3">Avaliar Produtos Comprados</h3>

        <a href="<?php echo e(route('minha.conta')); ?>" class="btn-voltar mb-3">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="rating-card mb-3">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-start gap-3">
                        <?php if(!empty($product->photo)): ?>
                            <img src="<?php echo e(asset('storage/'.$product->photo)); ?>" alt="<?php echo e($product->name); ?>" class="thumb">
                        <?php else: ?>
                            <div class="thumb thumb-placeholder d-flex align-items-center justify-content-center">
                                <i class="bi bi-image"></i>
                            </div>
                        <?php endif; ?>

                        <div class="flex-grow-1">
                            <strong class="product-name"><?php echo e($product->name); ?></strong>
                            <p class="product-desc mb-0"><?php echo e($product->description); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 text-md-end">
                    <?php if(in_array($product->id, $reviews)): ?>
                        <span class="badge text-bg-success px-3 py-2"><i class="bi bi-check2-circle me-1"></i>Você já avaliou</span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (! (in_array($product->id, $reviews))): ?>
                <form action="<?php echo e(route('products.reviews.store', $product->id)); ?>" method="POST" class="mt-3 rating-form" id="form-<?php echo e($product->id); ?>">
                    <?php echo csrf_field(); ?>

                    
                    <div class="rating-stars" data-field="rating">
                        <input type="radio" name="rating" id="r5-<?php echo e($product->id); ?>" value="5" required>
                        <label for="r5-<?php echo e($product->id); ?>" title="5"><i class="bi bi-star-fill"></i></label>

                        <input type="radio" name="rating" id="r4-<?php echo e($product->id); ?>" value="4">
                        <label for="r4-<?php echo e($product->id); ?>" title="4"><i class="bi bi-star-fill"></i></label>

                        <input type="radio" name="rating" id="r3-<?php echo e($product->id); ?>" value="3">
                        <label for="r3-<?php echo e($product->id); ?>" title="3"><i class="bi bi-star-fill"></i></label>

                        <input type="radio" name="rating" id="r2-<?php echo e($product->id); ?>" value="2">
                        <label for="r2-<?php echo e($product->id); ?>" title="2"><i class="bi bi-star-fill"></i></label>

                        <input type="radio" name="rating" id="r1-<?php echo e($product->id); ?>" value="1">
                        <label for="r1-<?php echo e($product->id); ?>" title="1"><i class="bi bi-star-fill"></i></label>
                    </div>
                    <div class="small text-muted" id="chosen-<?php echo e($product->id); ?>" aria-live="polite"></div>

                    
                    <div class="mt-3">
                        <label for="comment-<?php echo e($product->id); ?>" class="form-label fw-semibold">Comentário (opcional)</label>
                        <textarea id="comment-<?php echo e($product->id); ?>" name="comment" class="form-control" rows="2" placeholder="Conte como foi sua experiência"></textarea>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-send me-1"></i> Enviar Avaliação
                        </button>
                        <a href="<?php echo e(route('products.show')); ?>" class="btn btn-outline-dark">
                            <i class="bi bi-bag me-1"></i> Continuar comprando
                        </a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-4">
            <i class="bi bi-archive text-muted d-block mb-2" style="font-size:2rem;"></i>
            Você ainda não comprou nenhum produto.
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/account.myRatings.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/account.myRatings.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\agroconecta\resources\views/account/myRatings.blade.php ENDPATH**/ ?>