@extends('layouts.app')

@section('title', 'Meu Carrinho')
@section('boxed', true)

@section('content')
<h1 class="cart-title">Meu Carrinho</h1>

@php $total = 0; @endphp

<div class="cart-content">
    <div class="cart-table">
    @if (empty($cartItems))
        <p class="empty-cart">O carrinho está vazio.</p>
    @else
        <table class="table">
        <thead>
            <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
                @php
                  $subtotal = $item['price'] * $item['quantity'];
                  $total += $subtotal;
                @endphp
                <tr data-item-id="{{ $item['id'] }}">
                  <td>
                    <img src="{{ asset('storage/' . $item['photo']) }}" alt="{{ $item['name'] }}"
                        style="width: 50px; height: 50px; border-radius: 5px;">
                    {{ $item['name'] }}
                  </td>
                  <td>R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                  <td class="quantity-controls">
                    <div class="input-group" style="max-width: 140px;">
                      <button class="btn btn-outline-secondary btn-sm btn-decrease" type="button">-</button>
                     <input type="number" step="0.01" min="0.01"
                        class="form-control text-center quantity-input no-spin"readonly value="{{ number_format($item['quantity'], 2, '.', '') }}">
                      <button class="btn btn-outline-secondary btn-sm btn-increase" type="button">+</button>
                    </div>
                  </td>
                  <td class="subtotal">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                  <td>
                    <button type="button" class="btn btn-danger btn-sm delete-button"
                            data-item-id="{{ $item['id'] }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                      Remover
                    </button>
                  </td>
                </tr>
          @endforeach
        </tbody>
        </table>
    @endif
    </div>

    <div class="cart-summary">
        <h4>Resumo</h4>
        <p>Valor dos produtos: <span id="total-value">R$ {{ number_format($total, 2, ',', '.') }}</span></p>
        <p>Entrega: <span>Retirada com o produtor</span></p>
        <p>Desconto: <span id="discount-value">R$ 0,00</span></p>
        <hr>
        <p><strong>Total: <span id="grand-total">R$ {{ number_format($total, 2, ',', '.') }}</span></strong></p>
        <button class="btn btn-success btn-finalize" id="finalizarPedido">
            <span id="finalizarText">Finalizar Pedido</span>
            <span id="finalizarSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
        </button>
        <a class="btn btn-dark btn-finalize mt-2" href="{{ route('products.show') }}">Continuar Comprando</a>
    </div>
</div>
@endsection

@push('modals')
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Confirmação</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">Tem certeza de que deseja remover este item do carrinho?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger confirm-delete">Remover</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal finalizar pedido --}}
  <div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="finalizarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="finalizarModalLabel">Pedido Finalizado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">Pedido realizado com sucesso!</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
@endpush

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/cart.view.css') }}">
@endpush


@push('scripts')
  {{-- Config gerada no Blade para o JS público --}}
  <script id="cart-config" type="application/json">
    {
      "deleteUrl": "{{ route('cart.delete') }}",
      "checkoutUrl": "{{ route('cart.checkout') }}",
      "updateUrlBase": "{{ url('/cart/update') }}",
      "mpPublicKey": "{{ config('services.mercadopago.public_key') }}"
    }
  </script>

  {{-- Seu JS público --}}
  <script src="{{ asset('js/cart.view.js') }}" defer></script>
@endpush

