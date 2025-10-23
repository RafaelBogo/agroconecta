@extends('layouts.app')

@section('title', 'Mensagens')
@section('boxed', true)

@section('content')
  <div class="d-flex justify-content-between align-items-end mb-3">
    <div>
      <h2 class="mb-0">Minhas Conversas</h2>
      <small class="text-muted">Converse com compradores e vendedores</small>
    </div>
    @if (!$users->isEmpty())
      <span class="text-muted">{{ $users->count() }} conversa(s)</span>
    @endif
  </div>

  @if ($users->isEmpty())
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center py-5">
        <div class="mb-3" style="font-size:48px; line-height:1">
          <i class="bi bi-chat-dots"></i>
        </div>
        <h5 class="mb-1">Você ainda não tem conversas</h5>
        <p class="text-muted mb-3">Comece mandando mensagem em um produto.</p>
        <a href="{{ route('products.show') }}" class="btn btn-success">Explorar produtos</a>
      </div>
    </div>
  @else
    {{-- Busca simples --}}
    <div class="input-group mb-3">
      <span class="input-group-text bg-white">
        <i class="bi bi-search"></i>
      </span>
      <input id="chatSearch" type="text" class="form-control" placeholder="Buscar por nome…">
      <button id="clearSearch" class="btn btn-outline-secondary d-none" type="button">Limpar</button>
    </div>

    <div class="list-group list-group-flush bg-white rounded-3 shadow-sm overflow-hidden" id="chatList">
      @foreach ($users as $u)
        @php
          $unread   = $u->unread_count ?? 0;
          $initials = collect(explode(' ', trim($u->name)))
                        ->filter()
                        ->take(2)
                        ->map(fn($p) => mb_strtoupper(mb_substr($p,0,1)))
                        ->implode('');
        @endphp

        <a href="{{ route('chat.with', $u->id) }}"
           class="list-group-item list-group-item-action py-3 chat-item"
           data-name="{{ \Illuminate\Support\Str::lower($u->name) }}">

          <div class="d-flex align-items-center gap-3">
            {{-- Avatar com iniciais --}}
            <div class="avatar rounded-circle d-flex align-items-center justify-content-center">
              <span class="fw-semibold">{{ $initials ?: 'U' }}</span>
            </div>

            <div class="flex-grow-1">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-truncate">{{ $u->name }}</h6>
                @if ($unread > 0)
                  <span class="badge rounded-pill bg-success ms-2">{{ $unread }}</span>
                @else
                  <span class="text-muted small">Abrir chat</span>
                @endif
              </div>
            </div>
          </div>
        </a>
      @endforeach
    </div>
  @endif
@endsection

@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="{{ asset('css/chat.inbox.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/chat.inbox.js') }}" defer></script>
@endpush
