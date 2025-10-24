<?php $__env->startSection('title', 'Meus Dados'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>

<?php
      $cities = [
        'Chapecó','Xanxerê','Xaxim','Pinhalzinho','Palmitos','Maravilha','Modelo','Saudades','Águas de Chapecó',
        'Nova Erechim','Nova Itaberaba','Coronel Freitas','Quilombo','Abelardo Luz','Coronel Martins','Galvão',
        'São Lourenço do Oeste','Campo Erê','Saltinho','São Domingos','Ipuaçu','Entre Rios','Jupiá',
        'Itapiranga','Iporã do Oeste','Mondaí','Riqueza','Descanso','Tunápolis','Belmonte','Paraíso',
        'São Miguel do Oeste','Guaraciaba','Anchieta','Dionísio Cerqueira','Barra Bonita'
      ];
    ?>


    <h2>Meus Dados</h2>

    

    <form action="<?php echo e(route('user.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Seu Nome Completo</label>
                    <input type="text" id="name" name="name" class="form-control"
                           value="<?php echo e(old('name', $user->name)); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?php echo e($user->email); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="phone">Telefone</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                           placeholder="(00) 00000-0000"
                           value="<?php echo e(old('phone', $user->phone)); ?>" required>
                </div>

                <div class="form-group">
                    <label for="city">Cidade</label>
                    <select id="city" name="city" class="form-control" required>
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

            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Seu Endereço Completo</label>
                    <textarea id="address" name="address" class="form-control" rows="8"
                              placeholder="Cidade, comunidade/bairro, rua, ponto de referência, cor da casa..." required><?php echo e(old('address', $user->address)); ?></textarea>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <a href="<?php echo e(route('minha.conta')); ?>" class="btn btn-secondary">Voltar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </form>

    <!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal"
     data-success="<?php echo e(session('success') ? '1' : '0'); ?>"
     tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
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
  <link rel="stylesheet" href="<?php echo e(asset('css/account.myData.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/account.myData.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/myData.blade.php ENDPATH**/ ?>