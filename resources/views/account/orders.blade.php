@extends('layouts.app')

@section('title', 'Meus Pedidos')
@section('boxed', true)

@section('content')
    <h2 class="text-center mb-4">Meus Pedidos</h2>

    @forelse ($orders as $order)
        <div class="order-item mb-3 p-3 bg-white rounded shadow-sm d-flex justify-content-between align-items-start flex-wrap">
            <img src="{{ asset('storage/' . $order->product->photo) }}" alt="{{ $order->product->name }}" class="me-3 rounded" style="width: 80px; height: 80px; object-fit: cover;">

            <div class="order-details flex-fill">
                <p><strong>Produto:</strong> {{ $order->product->name }}</p>
                <p><strong>Preço Unitário:</strong> R$ {{ number_format($order->product->price, 2, ',', '.') }}</p>
                <p><strong>Total:</strong> R$ {{ number_format($order->total_price, 2, ',', '.') }}</p>
                <p><strong>Quantidade:</strong> {{ $order->quantity }}</p>
                <p><strong>Status:</strong> <span id="status-{{ $order->id }}">{{ $order->status }}</span></p>
                <p>
                    <strong>Tempo restante para cancelar:</strong>
                    <div class="cancel-timer text-danger" id="timer-{{ $order->id }}"
                         data-cancel-time-left="{{ $order->cancel_time_left }}"
                         data-status="{{ $order->status }}">
                        <span>{{ gmdate('i:s', $order->cancel_time_left) }}</span>
                    </div>
                </p>

                @php
                    $chatId = $order->seller_id ?? ($order->product->user_id ?? null);
                @endphp

                @if ($chatId)
                    <a href="{{ route('chat.with', ['userId' => $chatId]) }}" class="btn btn-sm btn-outline-primary mt-2">
                        Entrar em contato com o vendedor
                    </a>
                @else
                    <span class="badge bg-secondary mt-2">Contato do vendedor indisponível</span>
                @endif

            </div>

            @if ($order->status === 'Processando' && $order->cancel_time_left > 0)
                <div class="order-actions mt-3">
                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Cancelado">
                        <button type="submit" class="btn btn-danger btn-sm cancel-button" data-order-id="{{ $order->id }}">Cancelar Pedido</button>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <p class="text-center">Você ainda não realizou nenhum pedido.</p>
    @endforelse

    <div class="text-center mt-4">
        <a href="{{ route('minha.conta') }}" class="btn btn-dark px-4">Voltar</a>
    </div>
@endsection

@push('scripts')
<script>
    function startCountdown() {
        document.querySelectorAll('.cancel-timer').forEach(timer => {
            const cancelTimeLeft = parseInt(timer.getAttribute('data-cancel-time-left'), 10);
            const status = timer.getAttribute('data-status');

            if (status !== 'Processando') {
                timer.textContent = 'Não aplicável';
                return;
            }

            if (cancelTimeLeft > 0) {
                let timeLeft = cancelTimeLeft;

                const interval = setInterval(() => {
                    if (timeLeft > 0) {
                        timeLeft--;
                        const minutes = Math.floor(timeLeft / 60);
                        const seconds = timeLeft % 60;
                        timer.textContent = `${minutes}m ${seconds.toString().padStart(2, '0')}s`;
                    } else {
                        clearInterval(interval);
                        timer.textContent = 'Tempo para cancelamento expirado.';
                        const button = timer.closest('.order-item').querySelector('.btn-danger');
                        if (button) button.remove();
                    }
                }, 1000);
            } else {
                timer.textContent = 'Tempo para cancelamento expirado.';
            }
        });
    }

    function setupCancelButtons() {
        document.querySelectorAll('.cancel-button').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const orderId = this.getAttribute('data-order-id');
                const timerElement = document.getElementById(`timer-${orderId}`);
                const form = this.closest('form');

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(form)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pedido cancelado com sucesso!');
                        if (timerElement) {
                            timerElement.textContent = 'Cancelado';
                        }
                        const statusElement = document.querySelector(`#status-${orderId}`);
                        if (statusElement) {
                            statusElement.textContent = 'Cancelado';
                        }
                        this.remove();
                    } else {
                        alert('Erro ao cancelar o pedido. Tente novamente.');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao cancelar o pedido. Verifique sua conexão.');
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        startCountdown();
        setupCancelButtons();
    });
</script>
@endpush
