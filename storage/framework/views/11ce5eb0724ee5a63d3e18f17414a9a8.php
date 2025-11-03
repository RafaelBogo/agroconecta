<?php $__env->startSection('title', $product->name); ?>
<?php $__env->startSection('boxed', true); ?>
<?php $__env->startSection('back', route('products.show')); ?>

<?php
  use Illuminate\Support\Str;

  $decimais = in_array(strtolower($product->unit), ['kg','g','l','ml']);
  $isOwner  = auth()->check() && auth()->id() === $product->user_id;

  $foto = $product->photo;
  if (!Str::startsWith($foto, ['http://', 'https://'])) {
      $foto = route('media', ['path' => ltrim($product->photo, '/')]);
  }
?>

<?php $__env->startSection('content'); ?>
<div class="product-wrap">
  
  <div>
    <div class="product-image mb-4">
      <img
        src="<?php echo e($foto); ?>"
        alt="Foto do produto <?php echo e($product->name); ?>"
        onerror="this.src='https://via.placeholder.com/800x450?text=Sem+imagem';"
      >
    </div>

    
    <div class="reviews">
      <h4 class="mb-3">Avaliações dos Clientes</h4>
      <div class="reviews-scroll">
        <?php $__empty_1 = true; $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <div class="review-item mb-3">
            <div class="d-flex justify-content-between flex-wrap">
              <strong><?php echo e($review->user->name); ?></strong>
              <small class="text-muted"><?php echo e($review->created_at->format('d/m/Y')); ?></small>
            </div>
            <div class="mt-1 text-warning" aria-label="Nota: <?php echo e($review->rating); ?> de 5">
              <?php for($i = 1; $i <= 5; $i++): ?>
                <i class="bi <?php echo e($i <= $review->rating ? 'bi-star-fill' : 'bi-star'); ?>" aria-hidden="true"></i>
              <?php endfor; ?>
              <span class="visually-hidden">Avaliação <?php echo e($review->rating); ?> de 5</span>
            </div>
            <p class="mt-2 mb-0"><?php echo e($review->comment); ?></p>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div class="text-center text-muted">Ainda não há avaliações para este produto.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  
  <div>
    <h1 class="product-title"><?php echo e($product->name); ?></h1>
    <div class="product-price mb-3">R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?></div>

    <?php if($isOwner): ?>
      <div class="alert alert-info mb-3">
        Você é o vendedor deste produto. Ações de compra e conversa estão desabilitadas.
      </div>

      <div class="action-row">
        <a href="<?php echo e(route('products.show')); ?>" class="btn btn-outline-dark">
          <i class="bi bi-bag me-1"></i> Continuar comprando
        </a>
      </div>
    <?php else: ?>
      
      <form id="add-to-cart-form"
            class="mb-3"
            data-cart-add-url="<?php echo e(route('cart.add')); ?>"
            data-login-url="<?php echo e(route('login')); ?>"
            data-product-id="<?php echo e($product->id); ?>">
        <label for="quantity" class="form-label">Quantidade</label>
        <input
          type="number"
          id="quantity"
          name="quantity"
          class="form-control"
          value="1"
          min="<?php echo e($decimais ? '0.01' : '1'); ?>"
          step="<?php echo e($decimais ? '0.01' : '1'); ?>"
          inputmode="decimal"
          required
        >

        <div class="action-row mt-3 d-flex flex-wrap gap-2">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-cart-plus me-1"></i> Adicionar ao Carrinho
          </button>

          <a href="<?php echo e(route('products.show')); ?>" class="btn btn-outline-dark">
            <i class="bi bi-bag me-1"></i> Continuar comprando
          </a>

          <?php if(!empty($product->user_id)): ?>
            <a href="<?php echo e(route('chat.with', $product->user_id)); ?>" class="btn btn-outline-success">
              <i class="bi bi-chat-dots me-1"></i> Conversar com o vendedor
            </a>
          <?php endif; ?>
        </div>
      </form>
    <?php endif; ?>

    <div class="description mt-4">
      <h4>Descrição</h4>
      <p><?php echo e($product->description); ?></p>
    </div>

    <div class="additional-info">
      <h4>Informações Adicionais</h4>
      <div class="info-grid">
        <div class="info-card">
          <div class="icon"><i class="bi bi-calendar-event"></i></div>
          <div>
            <div class="label">Validade</div>
            <p class="value mb-0"><?php echo e($product->validity); ?></p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-basket"></i></div>
          <div>
            <div class="label">Unidade</div>
            <p class="value mb-0"><?php echo e($product->unit); ?></p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-telephone"></i></div>
          <div>
            <div class="label">Contato</div>
            <p class="value mb-0"><?php echo e($product->contact); ?></p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-geo-alt"></i></div>
          <div>
            <div class="label">Endereço</div>
            <p class="value mb-0"><?php echo e($product->address); ?>, <?php echo e($product->city); ?></p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-person-badge"></i></div>
          <div>
            <div class="label">Vendedor</div>
            <p class="value mb-0"><?php echo e($product->user->name ?? '—'); ?></p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-box-seam"></i></div>
          <div>
            <div class="label">Estoque Disponível</div>
            <p class="value mb-0"><?php echo e($product->stock); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php $__env->startPush('modals'); ?>
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sucesso!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">Produto adicionado ao carrinho com sucesso!</div>
      <div class="modal-footer">
        <a href="<?php echo e(route('cart.view')); ?>" class="btn btn-success">Ir para o carrinho</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/products.details.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/products.details.css')); ?>">
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/products/details.blade.php ENDPATH**/ ?>