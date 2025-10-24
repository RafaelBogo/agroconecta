<?php $__env->startSection('title', 'Chat'); ?>
<?php $__env->startSection('boxed', false); ?>

<?php $__env->startSection('content'); ?>
  <div class="card shadow-sm border-0">

    
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0 text-truncate"><?php echo e($user->name); ?></h5>
      <div class="d-flex gap-2">
        <a href="<?php echo e(route('chat.inbox')); ?>" class="btn btn-light btn-sm">
          Voltar
        </a>
        <form action="<?php echo e(route('chat.end', $user->id)); ?>" method="POST"
              onsubmit="return confirm('Encerrar esta conversa?')">
          <?php echo csrf_field(); ?>
          <?php echo method_field('DELETE'); ?>
          <button type="submit" class="btn btn-danger btn-sm">
            Encerrar
          </button>
        </form>
      </div>
    </div>

    
<div id="chatBody" class="card-body" style="height:60vh; overflow-y:auto;">
  <?php $lastDate = null; ?>

  <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <?php
      $isMe    = $msg->sender_id == auth()->id();
      $msgDate = $msg->created_at->format('d/m/Y');
      $time    = $msg->created_at->format('H:i');
    ?>

    <?php if($msgDate !== $lastDate): ?>
      <?php $lastDate = $msgDate; ?>
      <div class="text-center my-2">
        <small class="text-muted"><?php echo e($msgDate); ?></small>
      </div>
    <?php endif; ?>

    <div class="d-flex mb-2 <?php echo e($isMe ? 'justify-content-end' : 'justify-content-start'); ?>">
      <div class="msg <?php echo e($isMe ? 'out' : 'in'); ?>">
        <div class="msg-content"><?php echo e($msg->message); ?></div>
        <div class="msg-meta"><?php echo e($isMe ? 'Você' : $user->name); ?> • <?php echo e($time); ?></div>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <p class="text-center text-muted my-5">Nenhuma mensagem ainda.</p>
  <?php endif; ?>
</div>


    
    <div class="card-footer">
      <form id="chatForm" action="<?php echo e(route('chat.send')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="receiver_id" value="<?php echo e($user->id); ?>">
        <div class="d-flex gap-2">
          <textarea id="chatInput" name="message" class="form-control" rows="1"
                    placeholder="Digite sua mensagem..." required
                    style="resize:none; max-height:120px;"></textarea>
          <button id="sendBtn" class="btn btn-success" type="submit">Enviar</button>
        </div>
      </form>
    </div>

  </div>

<?php $__env->startPush('styles'); ?>
  <link rel="stylesheet" href="<?php echo e(asset('css/chat.index.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="<?php echo e(asset('js/chat.index.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/chat/index.blade.php ENDPATH**/ ?>