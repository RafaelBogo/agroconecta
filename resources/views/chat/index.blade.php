@extends('layouts.app')

@section('title', 'Chat')
@section('boxed', false)

@section('content')
  <div class="card shadow-sm border-0">

    <div class="chat-header px-3 py-2">
      <div class="d-flex align-items-center justify-content-between gap-3 w-100">

        <div class="d-flex align-items-center gap-3">
          @php
            $initials = collect(explode(' ', trim($user->name)))
              ->filter()->take(2)->map(fn($p)=>mb_strtoupper(mb_substr($p,0,1)))->implode('');
            $lastSeen = $user->last_seen ?? null;
          @endphp

          <div class="rounded-circle d-flex align-items-center justify-content-center bg-white text-dark"
               style="width:40px;height:40px;">
            <span class="fw-semibold">{{ $initials ?: 'U' }}</span>
          </div>

          <div class="min-w-0">
            <h5 class="mb-0 text-white text-truncate">{{ $user->name }}</h5>
            <small class="text-white-50">
              {{ $lastSeen ? ('Visto ' . \Carbon\Carbon::parse($lastSeen)->diffForHumans()) : 'Conversas privadas' }}
            </small>
          </div>
        </div>

        {{-- Direita: Ações --}}
        <div class="d-flex gap-2">
          <a href="{{ route('messages') }}" class="btn btn-light btn-sm">
            Voltar
          </a>

          <form action="{{ route('chat.end', $user->id) }}" method="POST"
                onsubmit="return confirm('Encerrar esta conversa?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm">
                Encerrar
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- Corpo do chat --}}
    <div id="chatBody" class="card-body pt-3"
         style="height: 55vh; overflow-y: auto; background: #ffffff;">
      @php $lastDate = null; @endphp

      @forelse($messages as $msg)
        @php
          $isMe = $msg->sender_id == auth()->id();
          $msgDate = $msg->created_at->format('d/m/Y');
          $time    = $msg->created_at->format('H:i');
        @endphp

        {{-- Separador de dia --}}
        @if ($msgDate !== $lastDate)
          @php $lastDate = $msgDate; @endphp
          <div class="text-center my-3">
            <span class="badge bg-light text-dark border" style="font-weight:500;">
              {{ $msgDate }}
            </span>
          </div>
        @endif

        <div class="d-flex mb-2 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
          <div class="chat-bubble {{ $isMe ? 'me' : 'them' }}">
            <div class="chat-text">{{ $msg->message }}</div>
            <div class="chat-meta">{{ $isMe ? 'Você' : $user->name }} • {{ $time }}</div>
          </div>
        </div>
      @empty
        <p class="text-center text-muted my-5">Nenhuma mensagem ainda.</p>
      @endforelse
    </div>

    {{-- Área de digitação --}}
    <div class="card-footer bg-white border-0 pt-0">
      <form id="chatForm" action="{{ route('chat.send') }}" method="POST" class="mt-2">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
        <div class="d-flex align-items-end gap-2">
          <textarea id="chatInput" name="message" class="form-control"
                    placeholder="Digite sua mensagem..."
                    rows="1" required style="resize:none; max-height: 120px;"></textarea>
          <button id="sendBtn" class="btn btn-success px-4" type="submit">Enviar</button>
        </div>
        <small class="text-muted d-block mt-1">Pressione Enter para enviar • Shift+Enter para nova linha</small>
      </form>
    </div>
  </div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  /* Barra do topo do chat */
  .chat-header{
    background-color: #198754; /* Bootstrap success */
    border-top-left-radius: .5rem;
    border-top-right-radius: .5rem;
    position: sticky; /* fixa no topo quando rola */
    top: 0;
    z-index: 2;
  }

  .chat-bubble{
    max-width: 70%;
    border-radius: 14px;
    padding: 10px 12px;
    position: relative;
    box-shadow: 0 1px 2px rgba(0,0,0,.06);
  }
  .chat-bubble.me{
    background: #198754;
    color: #fff;
    border-top-right-radius: 6px;
  }
  .chat-bubble.them{
    background: #f1f3f5;
    color: #212529;
    border-top-left-radius: 6px;
  }
  .chat-text{
    white-space: pre-wrap;
    word-break: break-word;
  }
  .chat-meta{
    font-size: 12px;
    opacity: .85;
    margin-top: 4px;
  }
  @media (max-width: 576px){
    .chat-bubble{ max-width: 85%; }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const bodyEl   = document.getElementById('chatBody');
    const inputEl  = document.getElementById('chatInput');
    const formEl   = document.getElementById('chatForm');
    const sendBtn  = document.getElementById('sendBtn');

    // Auto-scroll para a última mensagem
    const scrollBottom = () => { bodyEl.scrollTop = bodyEl.scrollHeight; };
    scrollBottom();

    // Textarea auto-grow
    const autoGrow = () => {
      inputEl.style.height = 'auto';
      inputEl.style.height = Math.min(inputEl.scrollHeight, 120) + 'px';
    };
    inputEl.addEventListener('input', autoGrow);
    autoGrow();

    // Enter envia, Shift+Enter quebra linha
    inputEl.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        formEl.requestSubmit();
      }
    });

    // UX do envio
    formEl.addEventListener('submit', () => {
      sendBtn.disabled = true;
      sendBtn.textContent = 'Enviando...';
    });
  });
</script>
@endpush
