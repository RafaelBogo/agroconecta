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
      <label for="city" class="form-label">Cidade</label>
      <input type="text" id="city" name="city" class="form-control" value="<?php echo e(old('city')); ?>" required>
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
        <option value="t"      <?php if(old('unit')==='t'): echo 'selected'; endif; ?>>Tonelada (t)</option>
        <option value="l"      <?php if(old('unit')==='l'): echo 'selected'; endif; ?>>Litro (L)</option>
        <option value="ml"     <?php if(old('unit')==='ml'): echo 'selected'; endif; ?>>Mililitro (mL)</option>
        <option value="saca60" <?php if(old('unit')==='saca60'): echo 'selected'; endif; ?>>Saca 60 kg</option>
        <option value="caixa"  <?php if(old('unit')==='caixa'): echo 'selected'; endif; ?>>Caixa</option>
        <option value="maco"   <?php if(old('unit')==='maco'): echo 'selected'; endif; ?>>Maço</option>
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
<style>
  /* deixa a caixa um pouco mais larga só aqui */
  .content-box { max-width: 900px; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('sellTipsModal');
    if (!modalEl) return;

    // move o modal para fora da .content-box (evita clipping/overlay travado)
    document.body.appendChild(modalEl);

    const shouldShow = !localStorage.getItem('sellTipsSeen');
    if (shouldShow) {
      const tipsModal = new bootstrap.Modal(modalEl, {
        backdrop: true,  // permite clicar fora para fechar
        keyboard: true   // permite fechar com ESC
      });
      tipsModal.show();

      // marca como visto ao fechar
      modalEl.addEventListener('hidden.bs.modal', () => {
        localStorage.setItem('sellTipsSeen', '1');
        // foca no primeiro campo do formulário
        const first = document.querySelector('form input, form textarea, form select');
        if (first) first.focus();
      });
    }
  });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\sell\cadastroProduto.blade.php ENDPATH**/ ?>