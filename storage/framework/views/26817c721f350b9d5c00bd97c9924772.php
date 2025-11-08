<?php $__env->startSection('title', 'Minhas Avaliações'); ?>
<?php $__env->startSection('boxed', true); ?>
<?php $__env->startSection('back', route('myAccount')); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
        <h3 class="mb-0">Avaliar produtos comprados</h3>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            $raw  = (string) ($product->photo ?? '');
            $isUrl = $raw !== '' && (strpos($raw, 'http://') === 0 || strpos($raw, 'https://') === 0);
            $foto = $isUrl ? $raw : route('media', ['path' => ltrim($raw, '/')]);
            $eligibleNorm = array_map('intval', $eligibleIds ?? []);
            $jaAvaliou    = in_array((int)$product->id, array_map('intval', $reviews ?? []));
            $podeAvaliar  = in_array((int)$product->id, $eligibleNorm
        ?>

        <div class="rating-card mb-3">
            <div class="row g-3 align-items-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-start gap-3">
                        <?php if($raw): ?>
                            <img src="<?php echo e($foto); ?>" alt="<?php echo e($product->name); ?>" class="thumb"
                                 onerror="this.onerror=null;this.src='https://via.placeholder.com/120x120?text=Sem+imagem';">
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
                    <?php if($jaAvaliou): ?>
                        <span class="badge text-bg-success px-3 py-2">
                            <i class="bi bi-check2-circle me-1"></i> Você já avaliou
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (! ($jaAvaliou)): ?>
                <?php if($podeAvaliar): ?>
                    <form action="<?php echo e(route('products.reviews.store', $product->id)); ?>" method="POST"
                          class="mt-3 rating-form" id="form-<?php echo e($product->id); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="rating-stars mb-2">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                                <input type="radio" name="rating" id="star-<?php echo e($product->id); ?>-<?php echo e($i); ?>" value="<?php echo e($i); ?>" required>
                                <label for="star-<?php echo e($product->id); ?>-<?php echo e($i); ?>">
                                    <i class="bi bi-star-fill"></i>
                                </label>
                            <?php endfor; ?>
                        </div>

                        <div id="chosen-<?php echo e($product->id); ?>" class="text-muted small mb-2">Nenhuma nota selecionada</div>

                        <textarea name="comment" class="form-control mb-2" rows="2" placeholder="Comentário (opcional)"></textarea>
                        <button type="submit" class="btn btn-success btn-sm">Enviar avaliação</button>
                    </form>
                <?php else: ?>
                    <div class="mt-3">
                        <span class="badge text-bg-secondary px-3 py-2">
                            <i class="bi bi-clock me-1"></i> Aguardando retirada
                        </span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-4">
            <i class="bi bi-archive text-muted d-block mb-2" style="font-size:2rem;"></i>
            Você ainda não tem produtos para avaliar.
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/account.myRatings.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/account.myRatings.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/myRatings.blade.php ENDPATH**/ ?>