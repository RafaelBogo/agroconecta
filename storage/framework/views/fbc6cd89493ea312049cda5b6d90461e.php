<?php $__env->startSection('title', 'Meus Dados'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
    <h2>Meus Dados</h2>

    <form action="<?php echo e(route('user.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Seu Nome Completo</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Seu Nome Completo" value="<?php echo e($user->name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?php echo e($user->email); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="phone">Telefone</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="(XX) XXXXX-XXXX" value="<?php echo e($user->phone); ?>" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Seu Endereço Completo</label>
                    <textarea id="address" name="address" class="form-control" rows="8" placeholder="Cidade, comunidade/bairro, rua, ponto de referência, cor da casa..." required><?php echo e($user->address); ?></textarea>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <a href="<?php echo e(route('minha.conta')); ?>" class="btn btn-secondary">Voltar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </form>

    <!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sucesso!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Seus dados foram atualizados com sucesso!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Fechar</button>
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

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ccc;
        padding: 15px;
        font-size: 16px;
        width: 100%;
    }

    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
    }

    .btn-success {
        padding: 10px 30px;
    }

    .btn-secondary {
        padding: 10px 30px;
        background-color: black;
        border: none;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if(session('success')): ?>
<script>
    window.onload = function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    };
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\account\myData.blade.php ENDPATH**/ ?>