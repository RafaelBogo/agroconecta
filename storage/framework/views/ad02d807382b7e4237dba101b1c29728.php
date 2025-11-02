<?php $__env->startSection('title', 'Cadastro de Produto'); ?>
<?php $__env->startSection('boxed', true); ?>
<?php $__env->startSection('back', content: route('dashboard')); ?>

<?php $__env->startSection('content'); ?>
<?php
    $cities = [
        'Abelardo Luz','Águas de Chapecó','Anchieta','Barra Bonita','Belmonte','Bom Jesus',
        'Campo Erê','Chapecó','Coronel Freitas','Coronel Martins','Descanso','Dionísio Cerqueira',
        'Entre Rios','Galvão','Guaraciaba','Ipuaçu','Iporã do Oeste','Itapiranga','Jupiá','Maravilha',
        'Modelo','Mondaí','Nova Erechim','Nova Itaberaba','Palmitos','Paraíso','Pinhalzinho',
        'Quilombo','Riqueza','Saltinho','São Domingos','São Lourenço do Oeste','São Miguel do Oeste',
        'Saudades','Tunápolis','Xanxerê','Xaxim',
    ];
?>

<div class="mb-5 text-center">
    <h2 class="fw-semibold mb-1"><i class="bi bi-box-seam text-success me-2"></i>Cadastro de Produto</h2>
    <p class="text-muted">Preencha as informações do produto para colocá-lo à venda no AgroConecta.</p>
</div>

<?php if($errors->any()): ?>
    <div class="alert alert-danger mb-4">
        <ul class="mb-0 small">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($error); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('sell.store')); ?>" method="POST" enctype="multipart/form-data" class="row g-5">
    <?php echo csrf_field(); ?>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">
                    <i class="bi bi-info-circle text-success me-2"></i>Informações do Produto
                </h5>

                <div class="mb-3">
                    <label for="name" class="form-label">Nome do Produto</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="<?php echo e(old('name')); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="4"
                        placeholder="Descreva o produto, tipo, origem, diferenciais..." required><?php echo e(old('description')); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Preço (R$)</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01"
                            value="<?php echo e(old('price')); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Estoque Disponível</label>
                        <input type="number" id="stock" name="stock" class="form-control"
                            value="<?php echo e(old('stock')); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="unit" class="form-label">Unidade de Medida</label>
                        <select id="unit" name="unit" class="form-select" required>
                            <option value="" disabled <?php echo e(old('unit') ? '' : 'selected'); ?>>Selecione...</option>
                            <option value="un" <?php if(old('unit') === 'un'): echo 'selected'; endif; ?>>Unidade (un)</option>
                            <option value="dz" <?php if(old('unit') === 'dz'): echo 'selected'; endif; ?>>Dúzia (dz)</option>
                            <option value="kg" <?php if(old('unit') === 'kg'): echo 'selected'; endif; ?>>Quilo (kg)</option>
                            <option value="g" <?php if(old('unit') === 'g'): echo 'selected'; endif; ?>>Grama (g)</option>
                            <option value="l" <?php if(old('unit') === 'l'): echo 'selected'; endif; ?>>Litro (L)</option>
                            <option value="bandeja" <?php if(old('unit') === 'bandeja'): echo 'selected'; endif; ?>>Bandeja</option>
                            <option value="custom" <?php if(old('unit') === 'custom'): echo 'selected'; endif; ?>>Outra…</option>
                        </select>

                        <input type="text" id="unit_custom" name="unit_custom"
                            class="form-control mt-2 <?php echo e(old('unit') === 'custom' ? '' : 'd-none'); ?>"
                            placeholder="Digite a unidade (ex.: 'arroba 15 kg')" value="<?php echo e(old('unit_custom')); ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="validity" class="form-label">Validade</label>
                        <input type="date" id="validity" name="validity" class="form-control"
                            value="<?php echo e(old('validity')); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 d-flex flex-column">
                <h5 class="card-title mb-3">
                    <i class="bi bi-geo-alt text-success me-2"></i>Localização e Contato
                </h5>

                <div class="mb-3">
                    <label for="city" class="form-label">Cidade</label>
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
                            <option value="<?php echo e($c); ?>" <?php if(old('city') === $c): echo 'selected'; endif; ?>><?php echo e($c); ?></option>
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

                <div class="mb-3">
                    <label for="address" class="form-label">Endereço completo</label>
                    <input list="saved_addresses_list" id="address" name="address" class="form-control"
                        placeholder="Insira ou selecione um endereço" value="<?php echo e(old('address')); ?>" required>
                    <datalist id="saved_addresses_list">
                        <?php if(!empty(auth()->user()->addresses)): ?>
                            <?php $__currentLoopData = auth()->user()->addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($addr); ?>"><?php echo e($addr); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </datalist>
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Telefone para Contato</label>
                    <input type="text" id="contact" name="contact" class="form-control"
                        placeholder="(49) 99999-9999" value="<?php echo e(old('contact')); ?>" required>
                </div>

                <div class="mb-4">
                    <label for="photo" class="form-label">Foto do Produto</label>
                    <input type="file" id="photo" name="photo" class="form-control"
                        accept="image/jpeg,image/png,image/webp,image/avif" required>
                    <div class="form-text">Formatos aceitos: JPG, PNG, WEBP, AVIF — até 3 MB.</div>
                </div>

                <div class="mt-auto d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle me-1"></i> Cadastrar Produto
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>


<div class="modal fade" id="sellTipsModal" tabindex="-1" aria-labelledby="sellTipsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="sellTipsLabel">
                    <i class="bi bi-lightbulb-fill text-success me-2"></i>Dicas para vender melhor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Use boas fotos:</strong> claras, com fundo neutro e bem iluminadas.</li>
                    <li class="list-group-item"><strong>Descreva bem:</strong> tipo, quantidade, origem e diferenciais.</li>
                    <li class="list-group-item"><strong>Defina preço justo:</strong> pesquise o mercado e seus custos.</li>
                    <li class="list-group-item"><strong>Localização clara:</strong> informe cidade e ponto de referência.</li>
                    <li class="list-group-item"><strong>Atualize informações:</strong> validade, estoque e contato.</li>
                    <li class="list-group-item"><strong>Responda rápido:</strong> agilidade aumenta as vendas.</li>
                    <li class="list-group-item"><strong>Embale bem:</strong> se for enviar, proteja o produto.</li>
                </ul>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK, entendi</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="sellSuccessModal" tabindex="-1" aria-labelledby="sellSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="sellSuccessLabel">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>Sucesso!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <?php echo e(session('success_message') ?? 'Produto cadastrado com sucesso!'); ?>

            </div>
            <div class="modal-footer border-0">
                <a href="<?php echo e(route('account.myProducts')); ?>" class="btn btn-success">OK</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .content-box {
        padding: 60px !important; /* mais espaçamento interno */
        margin-top: 40px !important;
        margin-bottom: 60px !important;
    }

    .card {
        border-radius: 16px;
    }

    .form-label {
        font-weight: 500;
    }

    .card-body {
        min-height: 540px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/sell.cadastroProduto.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/sell/cadastroProduto.blade.php ENDPATH**/ ?>