@extends('layouts.app')
@section('title', 'Pedido concluído')

@section('content')
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h1 class="mb-3">Pedido #{{ $order->id }} concluído</h1>
                <p class="text-muted mb-1">Status atual: <strong>{{ $order->status }}</strong></p>
                @if($mpStatus)
                    <p class="text-muted">Status no Mercado Pago: <strong>{{ $mpStatus }}</strong></p>
                @endif
                <hr>

                <a href="{{ route('orders.index') }}" class="btn btn-success me-2">Ver meus pedidos</a>

                @php
                    use Illuminate\Support\Facades\Route;
                    $continueUrl =
                        (Route::has('products.index') ? route('products.index') :
                            (Route::has('home') ? route('home') :
                                (Route::has('welcome') ? route('welcome') : url('/'))));
                  @endphp
                <a href="{{ $continueUrl }}" class="btn btn-outline-primary">Continuar comprando</a>
            </div>
        </div>
    </div>
@endsection
