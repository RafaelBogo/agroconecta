<?php $__env->startSection('title', 'Editar Produto'); ?>
<?php $__env->startSection('boxed', true); ?>
<?php $__env->startSection('back', route('account.myProducts')); ?>

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

        $units = [
            'un' => 'Unidade (un)',
            'dz' => 'Dúzia (dz)',
            'kg' => 'Quilo (kg)',
            'g' => 'Grama (g)',
            'l' => 'Litro (L)',
            'bandeja' => 'Bandeja',
        ];

        $currentUnit = old('unit', $product->unit);
        $isCustom = !array_key_exists($currentUnit, $units);

        $rawPhoto = (string) ($product->photo ?? '');
        $isUrl = $rawPhoto !== '' && (
            strpos($rawPhoto, 'http://') === 0 ||
            strpos($rawPhoto, 'https://') === 0
        );

        if ($isUrl) {
            $fotoAtual = $rawPhoto;
        } else {
            $fotoAtual = route('media', ['path' => ltrim($rawPhoto, '/')]);
        }
    ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Editar Produto</h2>
            <p class="text-muted mb-0">Altere as informações do produto cadastrado.</p>
        </div>
    </div>

    <form action="<?php echo e(route('products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data" class="row g-4">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-box-seam me-2 text-success"></i>Informações principais
                    </h5>

                    <div class="mb-3 text-center">
                        <img src="<?php echo e($fotoAtual); ?>" alt="<?php echo e($product->name); ?>" class="img-fluid rounded mb-3 shadow-sm"
                            onerror="this.onerror=null;this.src='https://via.placeholder.com/400x225?text=Sem+imagem';">
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Trocar foto</label>
                        <input id="photo" type="file" name="photo" class="form-control"
                            accept="image/jpeg,image/png,image/webp,image/avif">
                        <div class="form-text">JPG, PNG, WEBP ou AVIF — até 3 MB.</div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nome do produto</label>
                        <input id="name" type="text" name="name" class="form-control"
                            value="<?php echo e(old('name', $product->name)); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea id="description" name="description" class="form-control" rows="3"
                            required><?php echo e(old('description', $product->description)); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Preço (R$)</label>
                            <input id="price" type="number" step="0.01" name="price" class="form-control"
                                value="<?php echo e(old('price', $product->price)); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="stock" class="form-label">Estoque</label>
                            <input id="stock" type="number" name="stock" class="form-control"
                                value="<?php echo e(old('stock', $product->stock)); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="validity" class="form-label">Validade</label>
                        <input id="validity" type="date" name="validity" class="form-control"
                            value="<?php echo e(old('validity', $product->validity)); ?>">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-truck me-2 text-success"></i>Localização e contato
                    </h5>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">Cidade</label>
                            <select id="city" name="city" class="form-select" required>
                                <option value="" disabled>Selecione sua cidade</option>
                                <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c); ?>" <?php if(old('city', $product->city) === $c): echo 'selected'; endif; ?>>
                                        <?php echo e($c); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php $currentCity = old('city', $product->city); ?>
                                <?php if($currentCity && !in_array($currentCity, $cities)): ?>
                                    <option value="<?php echo e($currentCity); ?>" selected><?php echo e($currentCity); ?> (outra)</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Telefone / WhatsApp</label>
                            <input id="contact" type="text" name="contact" class="form-control"
                                value="<?php echo e(old('contact', $product->contact)); ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Endereço completo</label>
                        <textarea id="address" name="address" class="form-control" rows="3"
                            placeholder="Local de retirada ou entrega, bairro, referência..."><?php echo e(old('address', $product->address)); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="unit" class="form-label">Unidade de medida</label>
                        <select id="unit" name="unit" class="form-select" required>
                            <option value="" disabled <?php echo e($currentUnit ? '' : 'selected'); ?>>Selecione…</option>
                            <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val); ?>" <?php if($currentUnit === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <option value="custom" <?php if($isCustom): echo 'selected'; endif; ?>>Outra…</option>
                        </select>
                        <input type="text" id="unit_custom" name="unit_custom"
                            class="form-control mt-2 <?php echo e($isCustom ? '' : 'd-none'); ?>"
                            placeholder="Digite a unidade (ex.: 'arroba 15 kg')"
                            value="<?php echo e(old('unit_custom', $isCustom ? $currentUnit : '')); ?>">
                    </div>

                    <div class="pt-2 d-flex gap-2 justify-content-end mt-auto">
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
                    O produto foi atualizado com sucesso!
                </div>
                <div class="modal-footer border-0">
                    <a href="<?php echo e(route('account.myProducts')); ?>" class="btn btn-success">Fechar</a>
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
    <script>
        
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/editProduct.blade.php ENDPATH**/ ?>