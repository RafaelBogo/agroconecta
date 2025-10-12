@extends('layouts.app')

@section('title', 'Chat')
@section('boxed', false)

@section('content')
  <div class="card shadow-sm border-0">

    {{-- Cabeçalho simples --}}
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0 text-truncate">{{ $user->name }}</h5>
      <div class="d-flex gap-2">
        <a href="{{ route('chat.inbox') }}" class="btn btn-light btn-sm">
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

    {{-- Lista de mensagens --}}
    <div id="chatBody" class="card-body" style="height:60vh; overflow-y:auto;">
      @php $lastDate = null; @endphp

      @forelse ($messages as $msg)
        @php
          $isMe    = $msg->sender_id == auth()->id();
          $msgDate = $msg->created_at->format('d/m/Y');
          $time    = $msg->created_at->format('H:i');
        @endphp

        @if ($msgDate !== $lastDate)
          @php $lastDate = $msgDate; @endphp
          <div class="text-center my-2">
            <small class="text-muted">{{ $msgDate }}</small>
          </div>
        @endif

        <div class="d-flex mb-2 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
          <div class="p-2 rounded {{ $isMe ? 'bg-success text-white' : 'bg-light' }}" style="max-width:70%;">
            <div style="white-space:pre-wrap; word-break:break-word;">
              {{ $msg->message }}
            </div>
            <div class="small opacity-75 mt-1">
              {{ $isMe ? 'Você' : $user->name }} • {{ $time }}
            </div>
          </div>
        </div>
      @empty
        <p class="text-center text-muted my-5">Nenhuma mensagem ainda.</p>
      @endforelse
    </div>

    {{-- Form de envio --}}
    <div class="card-footer">
      <form id="chatForm" action="{{ route('chat.send') }}" method="POST">
        @csrf
        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
        <div class="d-flex gap-2">
          <textarea id="chatInput" name="message" class="form-control" rows="1"
                    placeholder="Digite sua mensagem..." required
                    style="resize:none; max-height:120px;"></textarea>
          <button id="sendBtn" class="btn btn-success" type="submit">Enviar</button>
        </div>
      </form>
    </div>

  </div>
@endsection
