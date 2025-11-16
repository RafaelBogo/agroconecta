@extends('layouts.app')
@section('title', 'Pedido concluído')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center p-4">

                        <div class="mb-3">
                            <div class="rounded-circle border border-success d-inline-flex align-items-center justify-content-center"
                                style="width: 64px; height: 64px;">
                                <span class="fs-2 text-success">✓</span>
                            </div>
                        </div>

                        <h1 class="h4 mb-2">Pedido #{{ $order->id }} concluído</h1>

                        <p class="text-muted mb-1">
                            Status do pedido: <strong>{{ $order->status }}</strong>
                        </p>

                        @if ($mpStatus)
                            <p class="text-muted mb-3">
                                Status no Mercado Pago: <strong>{{ $mpStatus }}</strong>
                            </p>
                        @endif

                        <hr class="my-4">

                        @php
                            $firstItem = $order->items->first();
                            $continueUrl = isset($firstItem)
                                ? route('products.show', $firstItem->product_id ?? $firstItem->product->id ?? null)
                                : route('products.index');
                        @endphp

                        <div class="d-flex flex-column flex-md-row justify-content-center gap-2">
                            <a href="{{ route('orders.index') }}" class="btn btn-success">
                                Ver meus pedidos
                            </a>

                            <a href="{{ $continueUrl }}" class="btn btn-outline-primary">
                                Continuar comprando
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
