<?php $__env->startSection('title', 'Chat'); ?>
<?php $__env->startSection('boxed', false); ?>

<?php $__env->startSection('content'); ?>
  <div class="chat-card shadow-lg border-0">

    
    <div class="chat-header px-3 py-2">
      <div class="d-flex align-items-center justify-content-between gap-3 w-100">
        <div class="d-flex align-items-center gap-3">
          <?php
            $initials = collect(explode(' ', trim($user->name)))
              ->filter()->take(2)->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->implode('');
            $lastSeen = $user->last_seen ?? null;
          ?>
          <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-dark"
               style="width:40px;height:40px;">
            <span class="fw-semibold"><?php echo e($initials ?: 'U'); ?></span>
          </div>
          <div class="min-w-0">
            <h5 class="mb-0 text-white text-truncate"><?php echo e($user->name); ?></h5>
            <small class="text-white-50">
              <?php echo e($lastSeen ? ('Visto ' . \Carbon\Carbon::parse($lastSeen)->diffForHumans()) : 'Conversas privadas'); ?>

            </small>
          </div>
        </div>

        <div class="d-flex gap-2">
          <a href="<?php echo e(route('messages')); ?>" class="btn btn-light btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>
          <form action="<?php echo e(route('chat.end', $user->id)); ?>" method="POST"
                onsubmit="return confirm('Encerrar esta conversa?')">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center gap-1">
              <i class="bi bi-x-circle"></i> Encerrar
            </button>
          </form>
        </div>
      </div>
    </div>

    
    <div id="chatBody" class="card-body pt-3 chat-body">
      <?php $lastDate = null; ?>
      <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
          $isMe = $msg->sender_id == auth()->id();
          $msgDate = $msg->created_at->format('d/m/Y');
          $time    = $msg->created_at->format('H:i');
        ?>

        <?php if($msgDate !== $lastDate): ?>
          <?php $lastDate = $msgDate; ?>
          <div class="text-center my-3">
            <span class="badge bg-light text-dark border" style="font-weight:500;">
              <?php echo e($msgDate); ?>

            </span>
          </div>
        <?php endif; ?>

        <div class="d-flex mb-2 <?php echo e($isMe ? 'justify-content-end' : 'justify-content-start'); ?>">
          <div class="chat-bubble <?php echo e($isMe ? 'me' : 'them'); ?>">
            <div class="chat-text"><?php echo e($msg->message); ?></div>
            <div class="chat-meta"><?php echo e($isMe ? 'Você' : $user->name); ?> • <?php echo e($time); ?></div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-center text-muted my-5">Nenhuma mensagem ainda.</p>
      <?php endif; ?>
    </div>

    
    <div class="chat-footer">
      <form id="chatForm" action="<?php echo e(route('chat.send')); ?>" method="POST" class="mt-2">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="receiver_id" value="<?php echo e($user->id); ?>">
        <div class="d-flex align-items-end gap-2">
          <textarea id="chatInput" name="message" class="form-control"
                    placeholder="Digite sua mensagem..."
                    rows="1" required style="resize:none; max-height: 120px;"></textarea>
          <button id="sendBtn" class="btn btn-success px-4" type="submit">
            <i class="bi bi-send"></i>
          </button>
        </div>
        <small class="text-muted d-block mt-1">Enter para enviar • Shift+Enter para nova linha</small>
      </form>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  .chat-card {
    background: transparent;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 6px 28px rgba(0,0,0,.12);
  }

  /* Cabeçalho */
  .chat-header {
    background: linear-gradient(90deg, rgba(25,135,84,.95), rgba(20,110,70,.95));
    backdrop-filter: blur(6px);
    border-bottom: 1px solid rgba(255,255,255,.2);
    padding: 12px 16px !important;
  }
  .chat-header h5 {
    font-weight: 600;
    color: #fff;
  }
  .chat-header small {
    color: rgba(255,255,255,.8);
  }
  .chat-header .rounded-circle {
    border: 2px solid rgba(255,255,255,.6);
  }

  /* Corpo do chat */
  .chat-body {
    height: 60vh;
    overflow-y: auto;
    background: #f7f9fa;
    padding: 12px;
  }

  /* Bolhas */
  .chat-bubble {
    max-width: 70%;
    border-radius: 16px;
    padding: 10px 14px;
    position: relative;
    box-shadow: 0 2px 5px rgba(0,0,0,.05);
    font-size: 0.95rem;
  }
  .chat-bubble.me {
    background: #198754;
    color: #fff;
    border-top-right-radius: 6px;
  }
  .chat-bubble.them {
    background: #fff;
    color: #222;
    border: 1px solid rgba(0,0,0,.08);
    border-top-left-radius: 6px;
  }

  /* Texto e meta */
  .chat-text {
    white-space: pre-wrap;
    word-break: break-word;
    line-height: 1.4;
  }
  .chat-meta {
    font-size: 12px;
    opacity: .75;
    margin-top: 4px;
  }

  /* Badge da data */
  .badge {
    padding: 6px 10px;
    font-size: 0.8rem;
    border-radius: 12px;
  }

  /* Rodapé */
  .chat-footer {
    background: #fff;
    border-top: 1px solid rgba(0,0,0,.08);
    padding: 12px;
  }
  #chatInput.form-control {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,.12);
    resize: none;
  }

  /* Scrollbar */
  .chat-body::-webkit-scrollbar { width: 8px; }
  .chat-body::-webkit-scrollbar-thumb {
    background: rgba(0,0,0,.15);
    border-radius: 8px;
  }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\chat\index.blade.php ENDPATH**/ ?>