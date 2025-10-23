@extends('layouts.app')

@section('title', 'Meus Pedidos')
@section('boxed', true)



@section('content')
    <div class="orders-header">
        <div>
            <h2>Meus Pedidos</h2>
            <div class="orders-sub">Acompanhe seus pedidos, converse com o vendedor e cancele dentro do prazo.</div>
        </div>
    </div>

    @forelse ($orders as $order)
        @php
            // Mapeia status para classes de chip
            $statusMap = [
                'Processando' => 'status-processando',
                'Confirmado'  => 'status-confirmado',
                'Enviado'     => 'status-enviado',
                'Entregue'    => 'status-entregue',
                'Cancelado'   => 'status-cancelado',
            ];
            $statusClass = $statusMap[$order->status] ?? 'status-processando';

            $chatId = $order->seller_id ?? ($order->product->user_id ?? null);
        @endphp

        <div class="order-card mb-3">
            <div class="order-grid">
                <img src="{{ asset('storage/' . $order->product->photo) }}"
                     alt="{{ $order->product->name }}"
                     class="order-thumb">

                <div class="order-details">
                    <p class="kv"><strong>Produto:</strong> {{ $order->product->name }}</p>
                    <p class="kv"><strong>Preço Unitário:</strong> R$ {{ number_format($order->product->price, 2, ',', '.') }}</p>
                    <p class="kv"><strong>Total:</strong> R$ {{ number_format($order->total_price, 2, ',', '.') }}</p>
                    <p class="kv"><strong>Quantidade:</strong> {{ $order->quantity }}</p>

                    <p class="kv">
                        <strong>Status:</strong>
                        <span class="status-chip {{ $statusClass }}" id="status-{{ $order->id }}">
                            <i class="bi bi-circle-fill" style="font-size:.6rem;"></i> {{ $order->status }}
                        </span>
                    </p>

                    <p class="kv mb-2">
                        <strong>Tempo restante para cancelar:</strong>
                        <span class="timer-pill cancel-timer"
                            id="timer-{{ $order->id }}"
                            data-status="{{ $order->status }}"
                            data-expires-at="{{ $order->cancel_expires_at ? $order->cancel_expires_at->toIso8601String() : '' }}"
                            data-cancel-time-left="{{ (int) $order->cancel_time_left }}">
                        <i class="bi bi-stopwatch"></i>
                        <span>{{ gmdate('i:s', max(0, (int)$order->cancel_time_left)) }}</span>
                        </span>
                    </p>


                    @if ($chatId)
                        <a href="{{ route('chat.with', ['userId' => $chatId]) }}"
                           class="btn btn-outline-success btn-sm btn-rounded me-2">
                            <i class="bi bi-chat-dots me-1"></i> Conversar com o vendedor
                        </a>
                    @else
                        <span class="badge text-bg-secondary">Contato do vendedor indisponível</span>
                    @endif
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