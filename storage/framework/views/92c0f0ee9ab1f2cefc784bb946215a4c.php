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
<style>
/* Caixa geral alinhada ao seu layout padrão */
.rating-card{
    background:#fff;
    border:1px solid rgba(0,0,0,.06);
    border-radius:18px;
    padding:16px 18px;
    box-shadow:0 6px 16px rgba(17,24,39,.06);
}

/* Cabeçalho do item */
.product-name{ font-size:1.05rem; color:#111827; }
.product-desc{ color:#6b7280; }

/* Thumb */
.thumb{
    width:72px; height:72px; object-fit:cover; border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
}
.thumb-placeholder{
    width:72px; height:72px; border-radius:12px; background:#f3f4f6; color:#9ca3af; font-size:1.3rem;
}

/* Estrelas (acessível + bonito) */
.rating-stars{
    direction: rtl; /* facilita selecionar “da direita p/ esquerda” */
    display:inline-flex; gap:6px; user-select:none;
}
.rating-stars input{
    display:none;
}
.rating-stars label{
    font-size:1.6rem; cursor:pointer; transition: transform .12s ease;
    color:#d1d5db; /* cinza claro quando “vazia” */
}
.rating-stars label:hover{ transform: translateY(-2px) scale(1.05); }
.rating-stars label i{ pointer-events:none; }

/* preenchimento ao passar e ao marcar */
.rating-stars input:checked ~ label i,
.rating-stars label:hover ~ label i{
    color:#f59e0b; /* amarelo */
}
.rating-stars input:checked + label i{
    color:#f59e0b;
}

/* Foco visível */
.rating-stars label:focus-visible{
    outline:2px solid rgba(25,135,84,.45); outline-offset:2px; border-radius:8px;
}

/* Botão voltar do seu layout já existe (.btn-voltar). Mantemos. */
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Texto dinâmico para a nota escolhida
document.querySelectorAll('.rating-form').forEach(function(form){
    const stars = form.querySelectorAll('.rating-stars input');
    const output = form.querySelector('#chosen-' + form.id.replace('form-', '') );

    stars.forEach(radio=>{
        radio.addEventListener('change', ()=>{
            output.textContent = `Nota selecionada: ${radio.value} de 5`;
        });
    });

    // Evita duplo submit
    form.addEventListener('submit', function(){
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Enviando...';
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\account\myRatings.blade.php ENDPATH**/ ?>