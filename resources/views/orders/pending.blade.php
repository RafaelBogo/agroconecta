@extends('layouts.app')
@section('title', 'Pagamento pendente')

@section('content')
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-body text-center">
      <h1 class="mb-3">Pagamento pendente</h1>
      <p class="text-muted">Pedido #{{ $order->id }}</p>
      <p>Assim que o Mercado Pago confirmar, atualizaremos o status.</p>
      <hr>
      <a href="{{ route('orders.index') }}" class="btn btn-secondary me-2">Ver meus pedidos</a>
      <a href="{{ route('products.show') }}" class="btn btn-outline-primary">Voltar às compras</a>
    </div>
  </div>
</div>
@endsection
