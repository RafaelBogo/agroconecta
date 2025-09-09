@extends('layouts.app')

@section('title', 'Meus Pedidos')
@section('boxed', true)

@push('styles')
    <style>
        .orders-header{
            display:flex; align-items:flex-end; justify-content:space-between; gap:12px; margin-bottom:18px;
        }
        .orders-header h2{ margin:0; font-weight:800; color:#111827; }
        .orders-sub{ color:#6b7280; }

        .order-card{
            border:1px solid rgba(0,0,0,.07);
            border-radius:16px;
            padding:16px;
            background:#fff;
            box-shadow:0 6px 18px rgba(17,24,39,.06);
        }
        .order-grid{
            display:grid;
            grid-template-columns: 92px 1fr auto;
            gap:14px;
            align-items:start;
        }
        @media (max-width: 768px){
            .order-grid{ grid-template-columns: 72px 1fr; }
            .order-actions{ grid-column: 1 / -1; }
        }
        .order-thumb{
            width:92px; height:92px; border-radius:12px; object-fit:cover; box-shadow:0 4px 10px rgba(0,0,0,.08);
        }
        .kv{ margin:0 0 4px; }
        .kv strong{ color:#374151; }
        .status-chip{
            display:inline-flex; align-items:center; gap:8px;
            padding:6px 10px; border-radius:999px; font-size:.85rem; font-weight:600;
            border:1px solid transparent;
        }
        .status-processando{ background:#fff7ed; color:#9a3412; border-color:#fed7aa; }
        .status-confirmado{ background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; }
        .status-enviado{ background:#ecfeff; color:#0369a1; border-color:#bae6fd; }
        .status-entregue{ background:#ecfdf5; color:#065f46; border-color:#bbf7d0; }
        .status-cancelado{ background:#f3f4f6; color:#374151; border-color:#e5e7eb; }

        .timer-pill{
            display:inline-flex; align-items:center; gap:6px;
            padding:6px 10px; border-radius:999px; font-size:.85rem; font-weight:600;
            background:rgba(220,38,38,.06); color:#b91c1c; border:1px solid rgba(220,38,38,.25);
        }
        .btn-rounded{ border-radius:12px; }
        .btn-voltar{
            background:#fff; color:#111827; border:1px solid rgba(0,0,0,.12);
            border-radius:10px; padding:8px 12px; font-weight:500; display:inline-flex; align-items:center; gap:8px;
            text-decoration:none; transition:background .15s ease, color .15s ease, transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }
        .btn-voltar:hover{ background:rgba(25,135,84,.10); color:#198754; border-color:rgba(25,135,84,.30); transform:translateY(-1px); box-shadow:0 6px 16px rgba(17,24,39,.08); text-decoration:none; }
        .empty-state{
            text-align:center; padding:40px 16px; background:#ffffff; border:1px dashed #e5e7eb; border-radius:16px;
            color:#6b7280;
        }
    </style>
@endpush

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
                              data-cancel-time-left="{{ (int) $order->cancel_time_left }}"
                              data-status="{{ $order->status }}">
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

@push('scripts')
<script>
(function(){
    function fmt(t){
        const m = Math.floor(t/60); const s = t%60;
        return `${m}m ${String(s).padStart(2,'0')}s`;
    }

    function startCountdown(){
        document.querySelectorAll('.cancel-timer').forEach(timer => {
            let timeLeft = parseInt(timer.getAttribute('data-cancel-time-left'), 10) || 0;
            const status  = timer.getAttribute('data-status');

            if(status !== 'Processando'){
                timer.textContent = 'Não aplicável';
                timer.classList.remove('timer-pill');
                return;
            }

            const spanVal = timer.querySelector('span') || timer;

            if(timeLeft <= 0){
                spanVal.textContent = 'Tempo para cancelamento expirado.';
                return;
            }

            const iv = setInterval(() => {
                timeLeft--;
                if(timeLeft > 0){
                    spanVal.textContent = fmt(timeLeft);
                } else {
                    clearInterval(iv);
                    spanVal.textContent = 'Tempo para cancelamento expirado.';
                    const card = timer.closest('.order-card');
                    const btn  = card ? card.querySelector('.cancel-button') : null;
                    if(btn){ btn.remove(); }
                }
            }, 1000);
        });
    }

    function setupCancelButtons(){
        document.querySelectorAll('.cancel-button').forEach(button => {
            button.addEventListener('click', function(e){
                e.preventDefault();
                const btn = this;
                const orderId = btn.getAttribute('data-order-id');
                const form = btn.closest('form');
                const timerEl = document.getElementById(`timer-${orderId}`);
                const statusEl = document.getElementById(`status-${orderId}`);

                // estado de carregamento
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Cancelando...';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: new FormData(form)
                })
                .then(r => r.ok ? r.json() : Promise.reject(r))
                .then(data => {
                    if(data.success){
                        // Atualiza status visual
                        if(statusEl){
                            statusEl.textContent = 'Cancelado';
                            statusEl.className = statusEl.className
                                .replace(/status\-\w+/g, '')
                                .trim() + ' status-cancelado';
                        }
                        if(timerEl){ timerEl.textContent = 'Cancelado'; }
                        btn.remove();
                        alert('Pedido cancelado com sucesso!');
                    } else {
                        throw new Error('Resposta de erro do servidor');
                    }
                })
                .catch(() => {
                    alert('Erro ao cancelar o pedido. Tente novamente.');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function(){
        startCountdown();
        setupCancelButtons();
    });
})();
</script>
@endpush
