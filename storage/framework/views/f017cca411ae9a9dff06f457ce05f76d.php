<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Finalizado</title>
</head>
<body>
    <h1>Olá, <?php echo e($orderDetails['user_name']); ?>!</h1>
    <p>Obrigado por realizar sua compra conosco!</p>

    <h3>Detalhes do Pedido:</h3>
    <ul>
        <?php $__currentLoopData = $orderDetails['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <?php echo e($item['quantity']); ?>x <?php echo e($item['name']); ?> - R$ <?php echo e(number_format($item['price'], 2, ',', '.')); ?>

                <br>
                <small><strong>Endereço de Retirada:</strong> <?php echo e($item['seller_address']); ?></small>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>

    <p><strong>Total:</strong> R$ <?php echo e(number_format($orderDetails['total'], 2, ',', '.')); ?></p>

    <p>Estamos à disposição para qualquer dúvida.</p>
    <p><strong>Equipe AgroConecta</strong></p>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\cart\pedido_finalizado.blade.php ENDPATH**/ ?>