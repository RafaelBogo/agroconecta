<?php $__env->startSection('title', 'Cadastro de Produto'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
  <h2 class="text-center mb-4">Cadastro de Produto</h2>

  <?php if($errors->any()): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($error); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php
      $cities = [
        'Chapecó','Xanxerê','Xaxim','Pinhalzinho','Palmitos','Maravilha','Modelo','Saudades','Águas de Chapecó',
        'Nova Erechim','Nova Itaberaba','Coronel Freitas','Quilombo','Abelardo Luz','Coronel Martins','Galvão',
        'São Lourenço do Oeste','Campo Erê','Saltinho','São Domingos','Ipuaçu','Entre Rios','Jupiá',
        'Itapiranga','Iporã do Oeste','Mondaí','Riqueza','Descanso','Tunápolis','Belmonte','Paraíso',
        'São Miguel do Oeste','Guaraciaba','Anchieta','Dionísio Cerqueira','Barra Bonita'
      ];
    ?>


  <form action="<?php echo e(route('sell.store')); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
    <?php echo csrf_field(); ?>

    <div class="col-12">
      <label for="name" class="form-label">Nome do Produto</label>
      <input type="text" id="name" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
    </div>

    <div class="col-12">
      <label for="description" class="form-label">Descrição</label>
      <textarea id="description" name="description" class="form-control" rows="3" required><?php echo e(old('description')); ?></textarea>
    </div>

    <div class="col-12 col-md-4">
      <label for="price" class="form-label">Preço</label>
      <input type="number" id="price" name="price" class="form-control" step="0.01" value="<?php echo e(old('price')); ?>" required>
    </div>

    <div class="col-12 col-md-4">
      <label for="city" class="form-label">Cidade (Oeste Catarinense)</label>
        <select id="city" name="city" class="form-select <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
          <option value="" disabled <?php echo e(old('city') ? '' : 'selected'); ?>>Selecione sua cidade</option>
             <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($c); ?>" <?php echo e(old('city') === $c ? 'selected' : ''); ?>><?php echo e($c); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
            <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>



    <div class="col-12">
      <label for="address" class="form-label">Endereço</label>
      <input list="saved_addresses_list" id="address" name="address" class="form-control" placeholder="Insira ou selecione um endereço" value="<?php echo e(old('address')); ?>" required>
      <datalist id="saved_addresses_list">
        <?php if(!empty(auth()->user()->addresses)): ?>
          <?php $__currentLoopData = auth()->user()->addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($addr); ?>"><?php echo e($addr); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </datalist>
    </div>

    <div class="col-12 col-md-4">
      <label for="stock" class="form-label">Estoque</label>
      <input type="number" id="stock" name="stock" class="form-control" value="<?php echo e(old('stock')); ?>" required>
    </div>

    <div class="col-12 col-md-4">
      <label for="unit" class="form-label">Unidade de Medida</label>
      <select id="unit" name="unit" class="form-select" required>
        <option value="" disabled <?php echo e(old('unit') ? '' : 'selected'); ?>>Selecione…</option>

        <option value="un"     <?php if(old('unit')==='un'): echo 'selected'; endif; ?>>Unidade (un)</option>
        <option value="dz"     <?php if(old('unit')==='dz'): echo 'selected'; endif; ?>>Dúzia (dz)</option>
        <option value="kg"     <?php if(old('unit')==='kg'): echo 'selected'; endif; ?>>Quilo (kg)</option>
        <option value="g"      <?php if(old('unit')==='g'): echo 'selected'; endif; ?>>Grama (g)</option>
        <option value="l"      <?php if(old('unit')==='l'): echo 'selected'; endif; ?>>Litro (L)</option>
        <option value="bandeja"<?php if(old('unit')==='bandeja'): echo 'selected'; endif; ?>>Bandeja</option>
      </select>

      <input
        type="text"
        id="unit_custom"
        name="unit_custom"
        class="form-control mt-2 <?php echo e(old('unit')==='custom' ? '' : 'd-none'); ?>"
        placeholder="Digite a unidade (ex.: 'arroba 15 kg')"
        value="<?php echo e(old('unit_custom')); ?>"
      >
    </div>

    <div class="col-12 col-md-4">
      <label for="validity" class="form-label">Validade</label>
      <input type="date" id="validity" name="validity" class="form-control" value="<?php echo e(old('validity')); ?>" required>
    </div>

    <div class="col-12 col-md-6">
      <label for="contact" class="form-label">Telefone para Contato</label>
      <input type="text" id="contact" name="contact" class="form-control" value="<?php echo e(old('contact')); ?>" required>
    </div>

    <div class="col-12 col-md-6">
      <label for="photo" class="form-label">Foto do Produto</label>
      <input type="file" id="photo" name="photo" class="form-control" required>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-success w-100">Cadastrar Produto</button>
    </div>
  </form>

  
  <div class="modal fade" id="sellTipsModal" tabindex="-1" aria-labelledby="sellTipsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0">
        <div class="modal-header">
          <h5 class="modal-title" id="sellTipsLabel">Dicas rápidas para vender melhor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Use boas fotos:</strong> claras, com fundo neutro; mostre detalhes.</li>
            <li class="list-group-item"><strong>Descreva bem:</strong> tipo, quantidade, origem, diferenciais (ex.: orgânico).</li>
            <li class="list-group-item"><strong>Preço justo:</strong> pesquise o mercado e considere seus custos.</li>
            <li class="list-group-item"><strong>Localização clara:</strong> cidade/bairro e referência ajudam o cliente.</li>
            <li class="list-group-item"><strong>Qualidade & validade:</strong> mantenha estoque e informações atualizadas.</li>
            <li class="list-group-item"><strong>Responda rápido:</strong> agilidade nas dúvidas converte mais.</li>
            <li class="list-group-item"><strong>Embale bem:</strong> se for enviar, proteja o produto no transporte.</li>
          </ul>
        </div>
        <div class="modal-footer">
          <button id="tips-ok-btn" type="button" class="btn btn-success" data-bs-dismiss="modal">OK, entendi</button>
        </div>
      </div>
    </div>
  </div>

  
  <div class="modal fade" id="sellSuccessModal" tabindex="-1" aria-labelledby="sellSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content border-0">
        <div class="modal-header">
          <h5 class="modal-title" id="sellSuccessLabel">Tudo certo!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <?php echo e(session('success_message') ?? 'Produto cadastrado com sucesso!'); ?>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/sell.cadastroProduto.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/sell.cadastroProduto.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/sell/cadastroProduto.blade.php ENDPATH**/ ?>