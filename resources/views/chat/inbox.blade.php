@extends('layouts.app')

@section('title', 'Mensagens')

@section('content')
  <h3 class="mb-4">Minhas Conversas</h3>

  @if ($users->isEmpty())
    <p class="text-muted">Você ainda não tem conversas.</p>
  @else
    <div class="list-group">
      @foreach ($users as $u)
        <a href="{{ route('chat.with', ['userId' => $u->id]) }}"
           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
          <span>{{ $u->name }}</span>
          <span class="btn btn-sm btn-outline-primary">Abrir chat</span>
        </a>
      @endforeach
    </div>
  @endif
@endsection
