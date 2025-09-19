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
            @foreach ($cartItems as $id => $item)
                @php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                @endphp
                <tr data-item-id="{{ $id }}">
                    <td>
                    <img src="{{ asset('storage/' . $item['photo']) }}" alt="{{ $item['name'] }}"
                            style="width: 50px; height: 50px; border-radius: 5px;">
                    {{ $item['name'] }}
                    </td>
                    <td>R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                    <td class="quantity-controls">
                    <div class="input-group" style="max-width: 120px;">
                        <button class="btn btn-outline-secondary btn-sm btn-decrease" type="button">-</button>
                        <input type="text" class="form-control text-center quantity-input"
                                value="{{ $item['quantity'] }}" readonly>
                        <button class="btn btn-outline-secondary btn-sm btn-increase" type="button">+</button>
                    </div>
                    </td>
                    <td class="subtotal">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                    <td>
                    <button type="button" class="btn btn-danger btn-sm delete-button"
                            data-item-id="{{ $id }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
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
  {{-- Modal remover item --}}
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
    <style>

    .cart-container {
        width: 1000px;
        margin: 24px auto;
        padding: 20px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,.1);
    }

    .cart-title {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .cart-content {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .cart-table {
        flex: 2;
        background: rgba(255,255,255,.9);
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,.1);
    }

    .cart-summary {
        flex: 1;
        background: #f5f5f5;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 6px rgba(0,0,0,.1);
    }

    .cart-summary h4 { font-weight: 700; color: #333; margin-bottom: 15px; }
    .cart-summary .btn-finalize { margin-top: 12px; width: 100%; }
    .empty-cart { text-align: center; font-size: 1.1rem; color: #555; margin: 12px 0; }
    </style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  // instâncias únicas dos modais
  const deleteModalEl = document.getElementById('deleteModal');
  const deleteModal   = bootstrap.Modal.getOrCreateInstance(deleteModalEl);
  const finModalEl    = document.getElementById('finalizarModal');
  const finModal      = bootstrap.Modal.getOrCreateInstance(finModalEl);

  // failsafe para quando um modal fechar,limpar as backdrops e body
  document.addEventListener('hidden.bs.modal', () => {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
  });

  // estado do item a remover
  let currentItemId = null;
  document.querySelectorAll('.delete-button').forEach(btn => {
    btn.addEventListener('click', function () {
      currentItemId = this.getAttribute('data-item-id');
    });
  });

  // confirmar remoção
  document.querySelector('.confirm-delete')?.addEventListener('click', async () => {
    if (!currentItemId) return;
    try {
      const r = await fetch("{{ route('cart.delete') }}", {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": csrf, "Content-Type": "application/json", "Accept":"application/json" },
        body: JSON.stringify({ item_id: currentItemId })
      });
      if (!r.ok) throw new Error('Falha ao remover');

      try { await r.json(); } catch (_) {}

      // remove linha na UI
      const row = document.querySelector(`tr[data-item-id="${currentItemId}"]`);
      if (row) row.remove();

      updateCartTotal();

      // mostra vazio se necessário
      if (!document.querySelector('.cart-table tbody tr')) {
        document.querySelector('.cart-table').innerHTML = '<p class="empty-cart">O carrinho está vazio.</p>';
      }

      deleteModal.hide();
    } catch (e) {
      console.error(e);
      alert('Não foi possível remover o item. Tente novamente.');
    } finally {
      currentItemId = null;
    }
  });

  // finalizar pedido
  const finalizarBtn   = document.getElementById('finalizarPedido');
  const spinner        = document.getElementById('finalizarSpinner');
  const finalizarText  = document.getElementById('finalizarText');

  finalizarBtn?.addEventListener('click', async () => {
    spinner.style.display = 'inline-block';
    finalizarText.style.display = 'none';
    finalizarBtn.disabled = true;

    try {
      const r = await fetch("{{ route('cart.finalizar') }}", { method: "POST", headers: { "X-CSRF-TOKEN": csrf, "Accept":"application/json" }});
      if (!r.ok) throw new Error('Falha ao finalizar');
      try { await r.json(); } catch (_) {}

      // limpa tabela e mostra modal
      document.querySelectorAll('.cart-table tbody tr').forEach(row => row.remove());
      document.querySelector('.cart-table').innerHTML = '<p class="empty-cart">O carrinho está vazio.</p>';
      updateCartTotal();
      finModal.show();
    } catch (e) {
      console.error(e);
      alert('Houve um erro ao finalizar o pedido.');
    } finally {
      spinner.style.display = 'none';
      finalizarText.style.display = 'inline-block';
      finalizarBtn.disabled = false;
    }
  });

  // quantidade + total
  document.querySelectorAll('.btn-increase, .btn-decrease').forEach(button => {
    button.addEventListener('click', function () {
      const row = this.closest('tr');
      const itemId = row.getAttribute('data-item-id');
      const input = row.querySelector('.quantity-input');
      let q = parseInt(input.value, 10);
      if (this.classList.contains('btn-increase')) q++;
      else if (q > 1) q--;

      input.value = q;
      updateSubtotal(row, q);

      fetch(`{{ url('cart/update') }}/${itemId}`, {
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrf, "Content-Type": "application/json", "Accept":"application/json" },
        body: JSON.stringify({ item_id: itemId, quantity: q })
      }).then(r => r.json()).then(d => { if (!d.success) alert('Erro ao atualizar o carrinho.'); })
        .catch(() => alert('Erro ao atualizar o carrinho. Verifique sua conexão.'));
    });
  });

  function updateSubtotal(row, quantity) {
    const price = parseFloat(row.querySelector('td:nth-child(2)').innerText.replace('R$ ','').replace(/\./g,'').replace(',','.'));
    const sub = (price * quantity).toFixed(2);
    row.querySelector('.subtotal').innerText = `R$ ${sub.replace('.', ',')}`;
    updateCartTotal();
  }

  function updateCartTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(el => {
      const v = parseFloat(el.innerText.replace('R$ ','').replace(/\./g,'').replace(',','.'));
      if (!isNaN(v)) total += v;
    });
    const txt = `R$ ${total.toFixed(2).replace('.', ',')}`;
    const totalValue = document.getElementById('total-value');
    const grandTotal = document.getElementById('grand-total');
    if (totalValue) totalValue.innerText = txt;
    if (grandTotal) grandTotal.innerText = txt;
  }
});
</script>
@endpush
