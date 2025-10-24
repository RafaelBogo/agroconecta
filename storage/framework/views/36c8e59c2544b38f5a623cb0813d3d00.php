<!DOCTYPE html>
<html>
<head>
    <title>Novo Pedido Recebido</title>
</head>
<body>
    <h1>Olá, <?php echo e($sellerDetails['seller_name']); ?></h1>
    <p>Você recebeu um novo pedido!</p>
    <h3>Detalhes do Pedido:</h3>
    <ul>
        <?php $__currentLoopData = $sellerDetails['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <strong>Produto:</strong> <?php echo e($item['name']); ?><br>
                <strong>Quantidade:</strong> <?php echo e($item['quantity']); ?><br>
                <strong>Comprador:</strong> <?php echo e($sellerDetails['buyer_name']); ?><br>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
    <p><strong>Total do Pedido:</strong> R$ <?php echo e(number_format($sellerDetails['total'], 2, ',', '.')); ?></p>
    <p>Obrigado por utilizar o AgroConecta!</p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/cart/pedido_finalizado_vendedor.blade.php ENDPATH**/ ?>