@extends('layouts.app')
@section('title', 'Pagamento não aprovado')

@section('content')
<div class="container py-5">
  <div class="card shadow-sm">
    <div class="card-body text-center">
      <h1 class="mb-3">Pagamento não aprovado</h1>
      <p class="text-muted">Pedido #{{ $order->id }}</p>
      <hr>
      <a href="{{ route('cart.view') }}" class="btn btn-warning me-2">Tentar novamente</a>
      <a href="{{ route('products.show') }}" class="btn btn-outline-primary">Voltar às compras</a>
    </div>
  </div>
</div>
@endsection
