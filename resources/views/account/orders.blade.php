@extends('layouts.app')

@section('title', 'Meus Pedidos')
@section('boxed', true)

@section('content')
  <div class="orders-header">
    <div>
      <h2>Meus Pedidos</h2>
      <div class="orders-sub">Acompanhe e manipule seus pedidos.</div>
    </div>
  </div>

  @forelse ($orders as $order)
    @php
      $statusMap = [
        'Pendente'=> 'status-pendente',
        'Concluido'=> 'status-concluido',
        'Cancelado'=> 'status-cancelado',
      ];
      $statusClass = $statusMap[$order->status] ?? 'status-processando';

      // Itens do pedido
      $items = $order->items ?? collect();

      if (isset($order->total_price) && $order->total_price > 0) {
        $orderTotal = $order->total_price;
      }

    @endphp

    <div class="order-card mb-3">
      <div class="order-grid">
        <div class="order-details">
          <p class="kv"><strong>Pedido:</strong> #{{ $order->id }}</p>

          {{-- Lista de itens do pedido --}}
          <div class="mb-2">
            @forelse ($items as $it)
              @php
                $p = $it->product;
                $nm = $p->name ?? 'Produto indisponível';
                $prc = ($it->price ?? ($p->price ?? 0)) * (int) $it->quantity;
              @endphp
              <div class="d-flex justify-content-between small">
                <span>{{ $nm }} (x{{ $it->quantity }})</span>
                <span>R$ {{ number_format($prc, 2, ',', '.') }}</span>
              </div>
            @empty
              <div class="text-muted small">Sem itens cadastrados neste pedido.</div>
            @endforelse
          </div>

          <p class="kv"><strong>Total do Pedido:</strong> R$ {{ number_format($orderTotal, 2, ',', '.') }}</p>

          <p class="kv">
            <strong>Status:</strong>
            <span class="status-chip {{ $statusClass }}" id="status-{{ $order->id }}">
              <i class="bi bi-circle-fill" style="font-size:.6rem;"></i> {{ $order->status }}
            </span>
          </p>
        </div>

        <div class="order-actions text-end">
          @if ($order->status === 'Processando' && $order->cancel_time_left > 0)
            <form action="{{ route('orders.update', $order->id) }}" method="POST" class="d-inline-block">
              @csrf
              @method('PUT')
              <input type="hidden" name="status" value="Cancelado">
              <button type="submit"
                      class="btn btn-danger btn-sm btn-rounded cancel-button"
                      data-order-id="{{ $order->id }}">
                <i class="bi bi-x-circle me-1"></i> Cancelar Pedido
              </button>
            </form>
          @endif
        </div>
      </div>
    </div>
  @empty
    <div class="empty-state mb-3">
      <i class="bi bi-bag-x" style="font-size:2rem;"></i>
      <p class="mt-2 mb-0">Você ainda não realizou nenhum pedido.</p>
    </div>
  @endforelse

  <a href="{{ route('minha.conta') }}" class="btn-voltar mt-2">
    <i class="bi bi-arrow-left"></i> Voltar
  </a>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/account.orders.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/account.orders.js') }}" defer></script>
@endpush
