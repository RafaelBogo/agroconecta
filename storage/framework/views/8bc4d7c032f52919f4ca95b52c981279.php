<?php $__env->startSection('title', 'Minhas Vendas'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="text-center mb-4">Minhas Vendas</h1>

    <?php if($vendas->isEmpty()): ?>
        <p class="text-center">Você ainda não possui vendas cadastradas.</p>
    <?php else: ?>
        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Comprador</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $vendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($venda->product->name); ?></td>
                        <td><?php echo e($venda->user->name); ?></td>
                        <td><?php echo e($venda->quantity); ?></td>
                        <td>R$ <?php echo e(number_format($venda->total_price, 2, ',', '.')); ?></td>
                        <td><?php echo e($venda->status); ?></td>
                        <td>
                            <?php if($venda->status === 'Processando'): ?>
                                <form action="<?php echo e(route('seller.confirmRetirada')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="order_id" value="<?php echo e($venda->id); ?>">
                                    <button type="submit" class="btn btn-success btn-sm">Confirmar Retirada</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="<?php echo e(route('minha.conta')); ?>" class="btn btn-dark">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\account\mySales.blade.php ENDPATH**/ ?>