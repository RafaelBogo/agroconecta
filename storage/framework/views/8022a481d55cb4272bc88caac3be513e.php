<?php $__env->startSection('title', 'Editar Produto'); ?>
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

<?php
  $units = [
    'un'=> 'Unidade (un)',
    'dz'=> 'Dúzia (dz)',
    'kg'=> 'Quilo (kg)',
    'g'=> 'Grama (g)',
    'l'=> 'Litro (L)',
    'bandeja'=> 'Bandeja',
  ];
  $currentUnit = old('unit', $product->unit);
  $isCustom    = !array_key_exists($currentUnit, $units);
?>



    <div class="left-section">
        <h4>Editar Produto</h4>
        <p>Aqui você altera informações sobre o seu produto</p>
        <img src="<?php echo e(asset('storage/' . $product->photo)); ?>" alt="<?php echo e($product->name); ?>" class="product-image">
    </div>
    <div class="right-section">
        <form action="<?php echo e(route('products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>




    <div class="mb-3">
        <label for="photo" class="form-label">Nova foto</label>
        <input id="photo" type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp,image/avif">
        <div class="form-text">JPG, PNG, WEBP ou AVIF — até 3 MB.</div>
    </div>


    <div class="mb-3">
        <label for="name" class="form-label">Nome do Produto</label>
        <input id="name" type="text" name="name" class="form-control" value="<?php echo e(old('name', $product->name)); ?>" required>
    </div>


    <div class="mb-3">
        <label for="description" class="form-label">Descrição do Produto</label>
        <textarea id="description" name="description" class="form-control" rows="4" required><?php echo e(old('description', $product->description)); ?></textarea>
    </div>


    <div class="mb-3">
        <label for="price" class="form-label">Valor (R$)</label>
        <input id="price" type="number" name="price" class="form-control" step="0.01" value="<?php echo e(old('price', $product->price)); ?>" required>
</div>


    <div class="mb-3">
        <label for="city" class="form-label">Cidade (Oeste Catarinense)</label>
        <select id="city" name="city" class="form-select" required>
                <option value="" disabled>Selecione sua cidade</option>
            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c); ?>" <?php if(old('city', $product->city) === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php $currentCity = old('city', $product->city); ?>
            <?php if($currentCity && !in_array($currentCity, $cities)): ?>
                <option value="<?php echo e($currentCity); ?>" selected><?php echo e($currentCity); ?> (outra)</option>
             <?php endif; ?>
        </select>
    </div>


    <div class="mb-3">
        <label for="stock" class="form-label">Estoque Disponível</label>
        <input id="stock" type="number" name="stock" class="form-control" value="<?php echo e(old('stock', $product->stock)); ?>" required>
    </div>


    <div class="mb-3">
        <label for="validity" class="form-label">Validade do Produto</label>
        <input id="validity" type="date" name="validity" class="form-control" value="<?php echo e(old('validity', $product->validity)); ?>">
    </div>

   <div class="mb-3">
        <label for="unit" class="form-label">Unidade de Medida</label>
        <select id="unit" name="unit" class="form-select" required>
        <option value="" disabled <?php echo e($currentUnit ? '' : 'selected'); ?>>Selecione…</option>
            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($val); ?>" <?php if($currentUnit === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <option value="custom" <?php if($isCustom): echo 'selected'; endif; ?>>Outra…</option>
        </select>

        <input type="text" id="unit_custom" name="unit_custom" class="form-control mt-2 <?php echo e($isCustom ? '' : 'd-none'); ?>" placeholder="Digite a unidade (ex.: 'arroba 15 kg')" value="<?php echo e(old('unit_custom', $isCustom ? $currentUnit : '')); ?>">
    </div>



    <div class="mb-3">
        <label for="contact" class="form-label">Telefone para Contato</label>
        <input id="contact" type="text" name="contact" class="form-control" value="<?php echo e(old('contact', $product->contact)); ?>">
    </div>


    <div class="mb-3">
        <label for="address" class="form-label">Endereço Completo</label>
        <textarea id="address" name="address" class="form-control" rows="3"><?php echo e(old('address', $product->address)); ?></textarea>
    </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="<?php echo e(route('account.myProducts')); ?>" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>

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
        O produto foi atualizado com sucesso!
      </div>

      <div class="modal-footer">
        <a href="<?php echo e(route('account.myProducts')); ?>" class="btn btn-success">OK</a>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/account.editProduct.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/account.editProduct.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/editProduct.blade.php ENDPATH**/ ?>