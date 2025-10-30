<?php $__env->startSection('title', 'Minhas Vendas'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="mb-4">Minhas Vendas</h1>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Comprador</th>
                    <th>Itens</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>MP Status</th>
                    <th style="width:280px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $vendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $total = $order->total_price ?? $order->items->sum(fn($i) => $i->price * $i->quantity);
                        $meusItens = $order->items->filter(fn($i) => $i->product && $i->product->user_id === auth()->id());
                    ?>
                    <tr>
                        <td><?php echo e($order->id); ?></td>
                        <td>
                            <?php echo e($order->user?->name ?? '—'); ?>

                            <br>
                            <small><?php echo e($order->user?->email); ?></small>
                        </td>
                        <td>
                            <?php $__currentLoopData = $meusItens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    <?php echo e($item->quantity); ?>x <?php echo e($item->product?->name); ?>

                                    <small class="text-muted">R$ <?php echo e(number_format($item->price, 2, ',', '.')); ?></small>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>R$ <?php echo e(number_format($total, 2, ',', '.')); ?></td>
                        <td><?php echo e($order->status); ?></td>
                        <td><?php echo e($order->mp_status ?? '—'); ?></td>
                        <td>
                            <?php if($order->status === 'Concluido' && $order->mp_payment_id): ?>
                                
                                <form method="POST" action="<?php echo e(route('orders.refund', $order)); ?>" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-sm btn-warning mb-1"
                                        onclick="return confirm('Confirmar reembolso total deste pedido?')">
                                        Reembolsar total
                                    </button>
                                </form>

                                
                                <form method="POST" action="<?php echo e(route('orders.refund', $order)); ?>" class="d-inline-flex align-items-center gap-1 mb-1">
                                    <?php echo csrf_field(); ?>
                                    <input name="amount" type="number" step="0.01" min="0.01"
                                           class="form-control form-control-sm" style="width: 90px"
                                           placeholder="Valor">
                                    <button class="btn btn-sm btn-outline-warning"
                                            onclick="return confirm('Confirmar reembolso parcial?')">
                                        Parcial
                                    </button>
                                </form>
                            <?php else: ?>
                                <small class="text-muted">Sem ações</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7">Nenhuma venda encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($vendas->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/mySales.blade.php ENDPATH**/ ?>