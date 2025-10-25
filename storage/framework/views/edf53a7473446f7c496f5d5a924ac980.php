<?php $__env->startSection('title','Meus Pedidos'); ?>
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
      $cls = [
        'Pendente'  => 'status-pendente',
        'Concluido' => 'status-concluido',
        'Cancelado' => 'status-cancelado',
      ][$order->status] ?? 'status-pendente';

      $items   = $order->items;
      $total   = $items->sum(fn($i) => (float)$i->price * (int)$i->quantity);
      $modalId = 'orderModal-'.$order->id;
    ?>

    <div class="order-card mb-3">
      <div class="order-grid">
        <div class="order-details">
          <p class="kv"><strong>Pedido:</strong> #<?php echo e($order->id); ?></p>
          <div class="mb-2 small text-muted">
            <?php echo e($items->count()); ?> item(ns) · Criado em <?php echo e($order->created_at?->format('d/m/Y H:i')); ?>

          </div>
          <p class="kv"><strong>Total do Pedido:</strong> R$ <?php echo e(number_format($total, 2, ',', '.')); ?></p>
          <p class="kv">
            <strong>Status:</strong>
            <span class="status-chip <?php echo e($cls); ?>">
              <i class="bi bi-circle-fill" style="font-size:.6rem"></i> <?php echo e($order->status); ?>

            </span>
          </p>
        </div>

        <div class="order-actions text-end">
          <button type="button" class="btn btn-outline-primary btn-sm me-2"
                  data-bs-toggle="modal" data-bs-target="#<?php echo e($modalId); ?>">
            <i class="bi bi-receipt"></i> Ver detalhes
          </button>

          <?php if($order->status === 'Pendente'): ?>
            
            <form action="<?php echo e(route('orders.update', $order->id)); ?>" method="POST" class="d-inline">
              <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
              <input type="hidden" name="status" value="Concluido">
              <button class="btn btn-success btn-sm me-1">
                <i class="bi bi-check2-circle me-1"></i> Pedido retirado
              </button>
            </form>

            
            <form action="<?php echo e(route('orders.update', $order->id)); ?>" method="POST" class="d-inline">
              <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
              <input type="hidden" name="status" value="Cancelado">
              <button class="btn btn-danger btn-sm">
                <i class="bi bi-x-circle me-1"></i> Cancelar
              </button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php $__env->startPush('modals'); ?>
      <div class="modal fade" id="<?php echo e($modalId); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Pedido #<?php echo e($order->id); ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
              <div class="d-flex flex-wrap gap-3 small mb-3">
                <div><strong>Status:</strong> <span class="status-chip <?php echo e($cls); ?>"><?php echo e($order->status); ?></span></div>
                <div><strong>Criado em:</strong> <?php echo e($order->created_at?->format('d/m/Y H:i')); ?></div>
                <div><strong>Total:</strong> R$ <?php echo e(number_format($total, 2, ',', '.')); ?></div>
              </div>

              <div class="table-responsive">
                <table class="table table-sm align-middle">
                  <thead>
                    <tr>
                      <th>Produto</th>
                      <th>Vendedor</th>
                      <th class="text-center">Qtd</th>
                      <th class="text-end">Preço</th>
                      <th class="text-end">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $__empty_2 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                      <?php
                        $p    = $it->product;
                        $unit = (float)$it->price;
                        $qty  = (int)$it->quantity;
                      ?>
                      <tr>
                        <td><?php echo e($p->name); ?></td>
                        <td><?php echo e($p->user->name ?? 'Usuário'); ?></td>
                        <td class="text-center"><?php echo e($qty); ?></td>
                        <td class="text-end">R$ <?php echo e(number_format($unit, 2, ',', '.')); ?></td>
                        <td class="text-end">R$ <?php echo e(number_format($unit * $qty, 2, ',', '.')); ?></td>
                      </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                      <tr><td colspan="5" class="text-center text-muted">Sem itens.</td></tr>
                    <?php endif; ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th colspan="4" class="text-end">Total</th>
                      <th class="text-end">R$ <?php echo e(number_format($total, 2, ',', '.')); ?></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

            <div class="modal-footer">
              <?php if($order->status === 'Pendente'): ?>
                <form action="<?php echo e(route('orders.update', $order->id)); ?>" method="POST" class="me-auto">
                  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                  <input type="hidden" name="status" value="Concluido">
                  <button class="btn btn-success btn-sm">
                    <i class="bi bi-check2-circle me-1"></i> Pedido retirado
                  </button>
                </form>
              <?php endif; ?>

              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
          </div>
        </div>
      </div>
    <?php $__env->stopPush(); ?>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state mb-3">
      <i class="bi bi-bag-x" style="font-size:2rem"></i>
      <p class="mt-2 mb-0">Você ainda não realizou nenhum pedido.</p>
    </div>
  <?php endif; ?>

  <a href="<?php echo e(route('minha.conta')); ?>" class="btn-voltar mt-2">
    <i class="bi bi-arrow-left"></i> Voltar
  </a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/account.orders.css')); ?>">
  <style>
    .status-chip{
      display:inline-flex;align-items:center;gap:.4rem;
      padding:.25rem .6rem;border-radius:9999px;
      font-weight:600;line-height:1;border:1px solid currentColor;
    }
    .status-pendente  { color:#946200; background:#fff7e6; border-color:#f5c06a; }
    .status-concluido { color:#0a6b2d; background:#e9f9ef; border-color:#7fd19a; }
    .status-cancelado { color:#842029; background:#f8d7da; border-color:#f1aeb5; }

    .modal          { z-index:2000 !important; }
    .modal-backdrop { z-index:1990 !important; }
  </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/orders.blade.php ENDPATH**/ ?>