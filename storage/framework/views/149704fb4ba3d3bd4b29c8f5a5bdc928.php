<?php $__env->startSection('title', 'Meus Pedidos'); ?>
<?php $__env->startSection('boxed', true); ?>



<?php $__env->startSection('content'); ?>
    <div class="orders-header">
        <div>
            <h2>Meus Pedidos</h2>
            <div class="orders-sub">Acompanhe seus pedidos, converse com o vendedor e cancele dentro do prazo.</div>
        </div>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
            // Mapeia status para classes de chip
            $statusMap = [
                'Processando' => 'status-processando',
                'Confirmado'  => 'status-confirmado',
                'Enviado'     => 'status-enviado',
                'Entregue'    => 'status-entregue',
                'Cancelado'   => 'status-cancelado',
            ];
            $statusClass = $statusMap[$order->status] ?? 'status-processando';

            $chatId = $order->seller_id ?? ($order->product->user_id ?? null);
        ?>

        <div class="order-card mb-3">
            <div class="order-grid">
                <img src="<?php echo e(asset('storage/' . $order->product->photo)); ?>"
                     alt="<?php echo e($order->product->name); ?>"
                     class="order-thumb">

                <div class="order-details">
                    <p class="kv"><strong>Produto:</strong> <?php echo e($order->product->name); ?></p>
                    <p class="kv"><strong>Preço Unitário:</strong> R$ <?php echo e(number_format($order->product->price, 2, ',', '.')); ?></p>
                    <p class="kv"><strong>Total:</strong> R$ <?php echo e(number_format($order->total_price, 2, ',', '.')); ?></p>
                    <p class="kv"><strong>Quantidade:</strong> <?php echo e($order->quantity); ?></p>

                    <p class="kv">
                        <strong>Status:</strong>
                        <span class="status-chip <?php echo e($statusClass); ?>" id="status-<?php echo e($order->id); ?>">
                            <i class="bi bi-circle-fill" style="font-size:.6rem;"></i> <?php echo e($order->status); ?>

                        </span>
                    </p>

                    <p class="kv mb-2">
                        <strong>Tempo restante para cancelar:</strong>
                        <span class="timer-pill cancel-timer"
                            id="timer-<?php echo e($order->id); ?>"
                            data-status="<?php echo e($order->status); ?>"
                            data-expires-at="<?php echo e($order->cancel_expires_at ? $order->cancel_expires_at->toIso8601String() : ''); ?>"
                            data-cancel-time-left="<?php echo e((int) $order->cancel_time_left); ?>">
                        <i class="bi bi-stopwatch"></i>
                        <span><?php echo e(gmdate('i:s', max(0, (int)$order->cancel_time_left))); ?></span>
                        </span>
                    </p>


                    <?php if($chatId): ?>
                        <a href="<?php echo e(route('chat.with', ['userId' => $chatId])); ?>"
                           class="btn btn-outline-success btn-sm btn-rounded me-2">
                            <i class="bi bi-chat-dots me-1"></i> Conversar com o vendedor
                        </a>
                    <?php else: ?>
                        <span class="badge text-bg-secondary">Contato do vendedor indisponível</span>
                    <?php endif; ?>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\agroconecta\resources\views/account/orders.blade.php ENDPATH**/ ?>