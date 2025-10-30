{{-- resources/views/account/mySales.blade.php --}}
@extends('layouts.app')

@section('title', 'Minhas Vendas')
@section('boxed', true)

@section('content')
    <h1 class="mb-4">Minhas Vendas</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Comprador</th>
                    <th>Itens</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>MP Status</th>
                    <th style="width:280px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendas as $order)
                    @php
                        $total = $order->total_price ?? $order->items->sum(fn($i) => $i->price * $i->quantity);
                        $meusItens = $order->items->filter(fn($i) => $i->product && $i->product->user_id === auth()->id());
                    @endphp
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>
                            {{ $order->user?->name ?? '—' }}
                            <br>
                            <small>{{ $order->user?->email }}</small>
                        </td>
                        <td>
                            @foreach($meusItens as $item)
                                <div>
                                    {{ $item->quantity }}x {{ $item->product?->name }}
                                    <small class="text-muted">R$ {{ number_format($item->price, 2, ',', '.') }}</small>
                                </div>
                            @endforeach
                        </td>
                        <td>R$ {{ number_format($total, 2, ',', '.') }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ $order->mp_status ?? '—' }}</td>
                        <td>
                            @if($order->status === 'Concluido' && $order->mp_payment_id)
                                {{-- Reembolso total --}}
                                <form method="POST" action="{{ route('orders.refund', $order) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-warning mb-1"
                                        onclick="return confirm('Confirmar reembolso total deste pedido?')">
                                        Reembolsar total
                                    </button>
                                </form>

                                {{-- Reembolso parcial --}}
                                <form method="POST" action="{{ route('orders.refund', $order) }}" class="d-inline-flex align-items-center gap-1 mb-1">
                                    @csrf
                                    <input name="amount" type="number" step="0.01" min="0.01"
                                           class="form-control form-control-sm" style="width: 90px"
                                           placeholder="Valor">
                                    <button class="btn btn-sm btn-outline-warning"
                                            onclick="return confirm('Confirmar reembolso parcial?')">
                                        Parcial
                                    </button>
                                </form>
                            @else
                                <small class="text-muted">Sem ações</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Nenhuma venda encontrada.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $vendas->links() }}
@endsection
