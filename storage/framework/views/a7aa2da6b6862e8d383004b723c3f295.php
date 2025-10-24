<?php $__env->startSection('title', 'Mensagens'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
  <div class="d-flex justify-content-between align-items-end mb-3">
    <div>
      <h2 class="mb-0">Minhas Conversas</h2>
      <small class="text-muted">Converse com compradores e vendedores</small>
    </div>
    <?php if(!$users->isEmpty()): ?>
      <span class="text-muted"><?php echo e($users->count()); ?> conversa(s)</span>
    <?php endif; ?>
  </div>

  <?php if($users->isEmpty()): ?>
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center py-5">
        <div class="mb-3" style="font-size:48px; line-height:1">
          <i class="bi bi-chat-dots"></i>
        </div>
        <h5 class="mb-1">Você ainda não tem conversas</h5>
        <p class="text-muted mb-3">Comece mandando mensagem em um produto.</p>
        <a href="<?php echo e(route('products.show')); ?>" class="btn btn-success">Explorar produtos</a>
      </div>
    </div>
  <?php else: ?>
    
    <div class="input-group mb-3">
      <span class="input-group-text bg-white">
        <i class="bi bi-search"></i>
      </span>
      <input id="chatSearch" type="text" class="form-control" placeholder="Buscar por nome…">
      <button id="clearSearch" class="btn btn-outline-secondary d-none" type="button">Limpar</button>
    </div>

    <div class="list-group list-group-flush bg-white rounded-3 shadow-sm overflow-hidden" id="chatList">
      <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          $unread   = $u->unread_count ?? 0;
          $initials = collect(explode(' ', trim($u->name)))
                        ->filter()
                        ->take(2)
                        ->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))
                        ->implode('');
        ?>

        <a href="<?php echo e(route('chat.with', $u->id)); ?>"
           class="list-group-item list-group-item-action py-3 chat-item"
           data-name="<?php echo e(\Illuminate\Support\Str::lower($u->name)); ?>">

          <div class="d-flex align-items-center gap-3">
            
            <div class="avatar rounded-circle d-flex align-items-center justify-content-center">
              <span class="fw-semibold"><?php echo e($initials ?: 'U'); ?></span>
            </div>

            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-truncate"><?php echo e($u->name); ?></h6>
                <?php if($unread > 0): ?>
                  <span class="badge rounded-pill bg-success ms-2"><?php echo e($unread); ?></span>
                <?php else: ?>
                  <span class="text-muted small">Abrir chat</span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?php echo e(asset('css/chat.inbox.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/chat.inbox.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/chat/inbox.blade.php ENDPATH**/ ?>