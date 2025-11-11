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

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

@forelse ($orders as $order)
    @php
        $cls = [
            'Pendente' => 'status-pendente',
            'Concluido' => 'status-concluido',
            'Cancelado' => 'status-cancelado',
            'Retirado' => 'status-retirado',
        ][$order->status] ?? 'status-pendente';

        $items = $order->items;
        $total = $items->sum(fn($i) => (float) $i->price * (int) $i->quantity);
        $modalId = 'orderModal-' . $order->id;

        $podeMarcarRetirado = in_array($order->status, ['Pendente', 'Concluido'], true);
    @endphp

    <div class="order-card mb-3">
        <div class="order-grid">
            <div class="order-details">
                <p class="kv"><strong>Pedido:</strong> #{{ $order->id }}</p>
                <div class="mb-2 small text-muted">
                    {{ $items->count() }} item(ns) · Criado em {{ $order->created_at?->format('d/m/Y H:i') }}
                </div>
                <p class="kv"><strong>Total do Pedido:</strong> R$ {{ number_format($total, 2, ',', '.') }}</p>
                <p class="kv">
                    <strong>Status:</strong>
                    <span class="status-chip {{ $cls }}">
                        <i class="bi bi-circle-fill" style="font-size:.6rem"></i> {{ $order->status }}
                    </span>
                </p>
            </div>

            <div class="order-actions text-end">
                <button type="button" class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal"
                    data-bs-target="#{{ $modalId }}">
                    <i class="bi bi-receipt"></i> Ver detalhes
                </button>

                @if ($podeMarcarRetirado)
                    <form action="{{ route('orders.update', $order->id) }}" method="POST" class="d-inline">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="Retirado">
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-check2-circle me-1"></i> Pedido retirado
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @push('modals')
        <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Pedido #{{ $order->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="d-flex flex-wrap gap-3 small mb-3">
                            <div><strong>Status:</strong> <span class="status-chip {{ $cls }}">{{ $order->status }}</span></div>
                            <div><strong>Criado em:</strong> {{ $order->created_at?->format('d/m/Y H:i') }}</div>
                            <div><strong>Total:</strong> R$ {{ number_format($total, 2, ',', '.') }}</div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Vendedor</th>
                                        <th class="text-center">Qtd</th>
                                        <th class="text-end">Preço</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($items as $it)
                                        @php
                                            $p = $it->product;
                                            $unit = (float) $it->price;
                                            $qty = (int) $it->quantity;
                                          @endphp
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->user->name ?? 'Usuário' }}</td>
                                            <td class="text-center">{{ $qty }}</td>
                                            <td class="text-end">R$ {{ number_format($unit, 2, ',', '.') }}</td>
                                            <td class="text-end">R$ {{ number_format($unit * $qty, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Sem itens.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total</th>
                                        <th class="text-end">R$ {{ number_format($total, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        @if ($podeMarcarRetirado)
                            <form action="{{ route('orders.update', $order->id) }}" method="POST" class="me-auto">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="Retirado">
                                <button class="btn btn-success btn-sm">
                                    <i class="bi bi-check2-circle me-1"></i> Pedido retirado
                                </button>
                            </form>
                        @endif

                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@empty
    <div class="empty-state mb-3">
        <i class="bi bi-bag-x" style="font-size:2rem"></i>
        <p class="mt-2 mb-0">Você ainda não realizou nenhum pedido.</p>
    </div>
@endforelse

@section('back', content: route('myAccount'))

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.orders.css') }}">
@endpush
