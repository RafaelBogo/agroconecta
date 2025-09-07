@extends('layouts.app')

@section('title', 'Chat')
@section('boxed', false)

@section('content')
  <div class="chat-card shadow-lg border-0">

    {{-- Cabeçalho --}}
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

        <div class="d-flex gap-2">
          <a href="{{ route('messages') }}" class="btn btn-light btn-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>
          <form action="{{ route('chat.end', $user->id) }}" method="POST"
                onsubmit="return confirm('Encerrar esta conversa?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center gap-1">
              <i class="bi bi-x-circle"></i> Encerrar
            </button>
          </form>
        </div>
      </div>
    </div>

    {{-- Corpo do chat --}}
    <div id="chatBody" class="card-body pt-3 chat-body">
      @php $lastDate = null; @endphp
      @forelse($messages as $msg)
        @php
          $isMe = $msg->sender_id == auth()->id();
          $msgDate = $msg->created_at->format('d/m/Y');
          $time    = $msg->created_at->format('H:i');
        @endphp

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

    {{-- Area de digitação --}}
    <div class="chat-footer">
      <form id="chatForm" action="{{ route('chat.send') }}" method="POST" class="mt-2">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
        <div class="d-flex align-items-end gap-2">
          <textarea id="chatInput" name="message" class="form-control"
                    placeholder="Digite sua mensagem..."
                    rows="1" required style="resize:none; max-height: 120px;"></textarea>
          <button id="sendBtn" class="btn btn-success px-4" type="submit">
            <i class="bi bi-send"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('styles')
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

  /* Balões */
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
@endpush
