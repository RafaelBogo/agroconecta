<?php $__env->startSection('title', 'Meus Dados'); ?>
<?php $__env->startSection('boxed', true); ?>
<?php $__env->startSection('back', route('myAccount')); ?>

<?php $__env->startSection('content'); ?>

    <?php
        $cities = [
            'Chapecó',
            'Xanxerê',
            'Xaxim',
            'Pinhalzinho',
            'Palmitos',
            'Maravilha',
            'Modelo',
            'Saudades',
            'Águas de Chapecó',
            'Nova Erechim',
            'Nova Itaberaba',
            'Coronel Freitas',
            'Quilombo',
            'Abelardo Luz',
            'Coronel Martins',
            'Galvão',
            'São Lourenço do Oeste',
            'Campo Erê',
            'Saltinho',
            'São Domingos',
            'Ipuaçu',
            'Entre Rios',
            'Jupiá',
            'Itapiranga',
            'Iporã do Oeste',
            'Mondaí',
            'Riqueza',
            'Descanso',
            'Tunápolis',
            'Belmonte',
            'Paraíso',
            'São Miguel do Oeste',
            'Guaraciaba',
            'Anchieta',
            'Dionísio Cerqueira',
            'Barra Bonita'
        ];
    ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Meus Dados</h2>
            <p class="text-muted mb-0">Atualize suas informações de contato e endereço.</p>
        </div>
    </div>

    <form action="<?php echo e(route('user.update')); ?>" method="POST" class="row g-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-person-circle me-2 text-success"></i>Informações pessoais
                    </h5>

                    <div class="mb-3">
                        <label for="name" class="form-label">Seu nome completo</label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label d-flex justify-content-between">
                            <span>E-mail</span>
                            <span class="badge bg-secondary-subtle text-secondary">não editável</span>
                        </label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo e($user->email); ?>"
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefone</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="(00) 00000-0000"
                            value="<?php echo e(old('phone', $user->phone)); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">Cidade</label>
                        <select id="city" name="city" class="form-select" required>
                            <option value="" disabled <?php echo e(old('city', $user->city) ? '' : 'selected'); ?>>
                                Selecione sua cidade
                            </option>
                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($city); ?>" <?php echo e(old('city', $user->city) === $city ? 'selected' : ''); ?>>
                                    <?php echo e($city); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-geo-alt me-2 text-success"></i>Endereço completo
                    </h5>

                    <div class="mb-3 flex-grow-1">
                        <label for="address" class="form-label">Endereço</label>
                        <textarea id="address" name="address" class="form-control" rows="6"
                            placeholder="Cidade, comunidade/bairro, rua, ponto de referência, cor da casa..."
                            required><?php echo e(old('address', $user->address)); ?></textarea>
                    </div>

                    <div class="pt-2 d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-success">
                            Salvar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    
    <div class="modal fade" id="successModal" data-success="<?php echo e(session('success') ? '1' : '0'); ?>" tabindex="-1"
        aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>Sucesso!
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Seus dados foram atualizados com sucesso!
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/account.myData.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/account.myData.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/myData.blade.php ENDPATH**/ ?>