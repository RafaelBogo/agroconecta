<?php $__env->startSection('title', 'Meus Produtos'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
    <h2>Meus Produtos</h2>

    <?php if($products->isEmpty()): ?>
        <p class="text-center text-muted">Você ainda não tem produtos registrados.</p>
    <?php else: ?>
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product-item">
                <div class="product-info">
                    <img src="<?php echo e(asset('storage/' . $product->photo)); ?>" alt="<?php echo e($product->name); ?>">
                    <div>
                        <h5><?php echo e($product->name); ?></h5>
                        <p>R$ <?php echo e(number_format($product->price, 2, ',', '.')); ?> por unidade</p>
                    </div>
                </div>
                <div class="btn-container">
                    <a href="<?php echo e(route('products.edit', $product->id)); ?>" class="btn-edit">Editar</a>
                    <button type="button" class="btn-delete delete-button" data-id="<?php echo e($product->id); ?>">Deletar</button>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <a href="<?php echo e(route('minha.conta')); ?>" class="btn-voltar">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Deletar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja deletar este produto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger confirm-delete">Deletar</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
    }

    .product-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    .product-info {
        display: flex;
        align-items: center;
    }

    .product-info img {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        margin-right: 15px;
    }

    .btn-container {
        display: flex;
        gap: 10px;
    }

    .btn-edit {
        background-color: #007bff;
        border: none;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .btn-edit:hover {
        background-color: #0056b3;
    }

    .btn-delete {
        background-color: #dc3545;
        border: none;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .btn-delete:hover {
        background-color: #a71d2a;
    }

    .btn-secondary {
        padding: 10px 30px;
        background-color: black;
        border: none;
        color: white;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let currentProductId = null;
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            currentProductId = this.getAttribute('data-id');
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        });
    });

    document.querySelector('.confirm-delete').addEventListener('click', function () {
        if (currentProductId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/products/${currentProductId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) return response.json();
                throw new Error('Erro na resposta');
            })
            .then(data => {
                if (data.success) {
                    document.querySelector(`.delete-button[data-id="${currentProductId}"]`).closest('.product-item').remove();
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                } else {
                    alert(data.message || 'Erro ao deletar o produto.');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Ocorreu um erro ao deletar o produto.');
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\account\myProducts.blade.php ENDPATH**/ ?>