<?php $__env->startSection('title', 'Meus Pedidos'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
  <div class="orders-header">
    <div>
      <h2>Meus Pedidos</h2>
      <div class="orders-sub">Acompanhe e manipule seus pedidos.</div>
    </div>
  </div>

  <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
      $statusMap = [
        'Pendente' => 'status-pendente',
        'Concluido'  => 'status-concluido',
        'Cancelado'   => 'status-cancelado',
      ];
      $statusClass = $statusMap[$order->status] ?? 'status-processando';

      // Itens do pedido
      $items = $order->items ?? collect();

      if (isset($order->total_price) && $order->total_price > 0) {
        $orderTotal = $order->total_price;
      }

    ?>

    <div class="order-card mb-3">
      <div class="order-grid">
        <div class="order-details">
          <p class="kv"><strong>Pedido:</strong> #<?php echo e($order->id); ?></p>

          
          <div class="mb-2">
            <?php $__empty_2 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
              <?php
                $p   = $it->product;
                $nm  = $p->name  ?? 'Produto indisponível';
                $prc = ($it->price ?? ($p->price ?? 0)) * (int) $it->quantity;
              ?>
              <div class="d-flex justify-content-between small">
                <span><?php echo e($nm); ?> (x<?php echo e($it->quantity); ?>)</span>
                <span>R$ <?php echo e(number_format($prc, 2, ',', '.')); ?></span>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
              <div class="text-muted small">Sem itens cadastrados neste pedido.</div>
            <?php endif; ?>
          </div>

          <p class="kv"><strong>Total do Pedido:</strong> R$ <?php echo e(number_format($orderTotal, 2, ',', '.')); ?></p>

          <p class="kv">
            <strong>Status:</strong>
            <span class="status-chip <?php echo e($statusClass); ?>" id="status-<?php echo e($order->id); ?>">
              <i class="bi bi-circle-fill" style="font-size:.6rem;"></i> <?php echo e($order->status); ?>

            </span>
          </p>
        </div>

        <div class="order-actions text-end">
          <?php if($order->status === 'Processando' && $order->cancel_time_left > 0): ?>
            <form action="<?php echo e(route('orders.update', $order->id)); ?>" method="POST" class="d-inline-block">
              <?php echo csrf_field(); ?>
              <?php echo method_field('PUT'); ?>
              <input type="hidden" name="status" value="Cancelado">
              <button type="submit"
                      class="btn btn-danger btn-sm btn-rounded cancel-button"
                      data-order-id="<?php echo e($order->id); ?>">
                <i class="bi bi-x-circle me-1"></i> Cancelar Pedido
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state mb-3">
      <i class="bi bi-bag-x" style="font-size:2rem;"></i>
      <p class="mt-2 mb-0">Você ainda não realizou nenhum pedido.</p>
    </div>
  <?php endif; ?>

  <a href="<?php echo e(route('minha.conta')); ?>" class="btn-voltar mt-2">
    <i class="bi bi-arrow-left"></i> Voltar
  </a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/account.orders.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/account.orders.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/orders.blade.php ENDPATH**/ ?>