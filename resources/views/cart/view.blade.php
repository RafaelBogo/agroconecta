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
<style>
  .cart-container {
      width: 1000px;
      margin: 24px auto;
      padding: 20px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0,0,0,.1);
  }
  .cart-title { font-size: 2rem; font-weight: 700; text-align: center; margin-bottom: 20px; color: #333; }
  .cart-content { display: flex; gap: 20px; flex-wrap: wrap; }
  .cart-table { flex: 2; background: rgba(255,255,255,.9); border-radius: 10px; padding: 15px; box-shadow: 0 4px 6px rgba(0,0,0,.1); }
  .cart-summary { flex: 1; background: #f5f5f5; border-radius: 10px; padding: 15px; box-shadow: 0 4px 6px rgba(0,0,0,.1); }
  .cart-summary h4 { font-weight: 700; color: #333; margin-bottom: 15px; }
  .cart-summary .btn-finalize { margin-top: 12px; width: 100%; }
  .empty-cart { text-align: center; font-size: 1.1rem; color: #555; margin: 12px 0; }

  /* remove as setinhas do carrinho onde aumenta (Chrome/Edge/Safari) */
  .no-spin::-webkit-inner-spin-button,
  .no-spin::-webkit-outer-spin-button { 
  -webkit-appearance: none;
  margin: 0;
}

/* remove as setinhas do carrinho onde aumenta (Firefox) */
  .no-spin {
    -moz-appearance: textfield;
    appearance: textfield;
}

</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  console.log('[cart] script carregado');
  const csrf = (document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}').trim();

  const hasBootstrap = !!(window.bootstrap && bootstrap.Modal);
  const deleteModalEl = document.getElementById('deleteModal');
  const deleteModal   = hasBootstrap && deleteModalEl ? bootstrap.Modal.getOrCreateInstance(deleteModalEl) : null;

  document.addEventListener('hidden.bs.modal', () => {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
  });

  let currentItemId = null;

  document.addEventListener('click', async (ev) => {
    const btn = ev.target.closest('.delete-button');
    if (!btn) return;

    currentItemId = btn.getAttribute('data-item-id');

    if (deleteModal) {
      return;
    }

    if (confirm('Tem certeza de que deseja remover este item do carrinho?')) {
      await removeCurrentItem();
    }
  });

  document.querySelector('.confirm-delete')?.addEventListener('click', async () => {
    await removeCurrentItem();
    deleteModal?.hide();
  });

  async function removeCurrentItem() {
    if (!currentItemId) return;
    try {
      const r = await fetch("{{ route('cart.delete') }}", {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": csrf, "Content-Type": "application/json", "Accept":"application/json" },
        body: JSON.stringify({ item_id: currentItemId })
      });
      if (!r.ok) throw new Error('Falha ao remover');

      try { await r.json(); } catch (_) {}

      const row = document.querySelector(`tr[data-item-id="${currentItemId}"]`);
      if (row) row.remove();

      updateCartTotal();

      if (!document.querySelector('.cart-table tbody tr')) {
        document.querySelector('.cart-table').innerHTML = '<p class="empty-cart">O carrinho está vazio.</p>';
      }
    } catch (e) {
      console.error(e);
      alert('Não foi possível remover o item. Tente novamente.');
    } finally {
      currentItemId = null;
    }
  }

  // Finalizar pedido
  const finalizarBtn   = document.getElementById('finalizarPedido');
  const spinner        = document.getElementById('finalizarSpinner');
  const finalizarText  = document.getElementById('finalizarText');

  finalizarBtn?.addEventListener('click', async () => {
    if (finalizarBtn.dataset.loading === '1') return;
    finalizarBtn.dataset.loading = '1';
    spinner.style.display = 'inline-block';
    finalizarText.style.display = 'none';
    finalizarBtn.disabled = true;

    try {
      const r   = await fetch("{{ route('cart.checkout') }}", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": csrf, "Accept": "application/json" }
      });
      const raw = await r.text();
      let data;
      try { data = JSON.parse(raw); } catch { data = null; }

      console.log('[checkout] status:', r.status);
      console.log('[checkout] raw   :', raw);

      if (!r.ok) {
        const msg = (data && (data.mp_error || data.error || data.message)) || raw || `HTTP ${r.status}`;
        throw new Error(`Falha no checkout: ${msg}`);
      }

      const prefId = data?.preference_id ?? data?.id;
      if (!prefId) throw new Error('Resposta do servidor veio sem preference_id.');

      await ensureMercadoPagoLoaded();
      const pk = "{{ config('services.mercadopago.public_key') }}";
      if (!pk) throw new Error('PUBLIC KEY do Mercado Pago não configurada.');

      const mp = new MercadoPago(pk, { locale: 'pt-BR' });
      mp.checkout({ preference: { id: prefId }, autoOpen: true });
    } catch (e) {
      console.error('[checkout] erro:', e);
      alert(e?.message || 'Houve um erro ao finalizar o pedido.');
    } finally {
      spinner.style.display = 'none';
      finalizarText.style.display = 'inline-block';
      finalizarBtn.disabled = false;
      finalizarBtn.dataset.loading = '0';
    }
  });

  function ensureMercadoPagoLoaded() {
    return new Promise((resolve, reject) => {
      if (window.MercadoPago) return resolve();
      const existing = document.querySelector('script[src*="sdk.mercadopago.com/js/v2"]');
      if (existing) {
        existing.addEventListener('load', () => resolve(), { once: true });
        existing.addEventListener('error', () => reject(new Error('Falha ao carregar SDK do Mercado Pago.')), { once: true });
        return;
      }
      const s = document.createElement('script');
      s.src = 'https://sdk.mercadopago.com/js/v2';
      s.async = true;
      s.onload = () => resolve();
      s.onerror = () => reject(new Error('Falha ao carregar SDK do Mercado Pago.'));
      document.head.appendChild(s);
    });
  }

 document.addEventListener('click', function (ev) {
  const inc = ev.target.closest('.btn-increase');
  const dec = ev.target.closest('.btn-decrease');
  if (!inc && !dec) return;

  const row   = (inc || dec).closest('tr');
  if (!row) return;

  const itemId = row.getAttribute('data-item-id');      
  const input  = row.querySelector('.quantity-input');


  let q = parseFloat(String(input.value).replace(',', '.')) || 0;


  const step = parseFloat(input.getAttribute('step') || '1');
  const min  = parseFloat(input.getAttribute('min')  || String(step));

  if (inc) q = +(q + step).toFixed(2);
  if (dec) q = Math.max(min, +(q - step).toFixed(2));

 input.value = q.toFixed(2)

  updateSubtotal(row, q);

  fetch(`{{ url('/cart/update') }}/${itemId}`, {
    method: "PUT",
    headers: {
      "X-CSRF-TOKEN": csrf,
      "Content-Type": "application/json",
      "Accept": "application/json"
    },
    body: JSON.stringify({ quantity: q }) 
  })
  .then(r => r.ok ? r.json() : Promise.reject(r))
  .then(d => { if (!d.success) alert(d.message || 'Erro ao atualizar o carrinho.'); })
  .catch(() => alert('Erro ao atualizar o carrinho. Verifique sua conexão.'));
});


  function updateSubtotal(row, quantity) {
    const priceTxt = row.querySelector('td:nth-child(2)')?.innerText || 'R$ 0,00';
    const price = parseFloat(priceTxt.replace('R$','').replace(/\s/g,'').replace(/\./g,'').replace(',','.')) || 0;
    const sub = (price * quantity).toFixed(2);
    const subEl = row.querySelector('.subtotal');
    if (subEl) subEl.innerText = `R$ ${sub.replace('.', ',')}`;
    updateCartTotal();
  }

  function updateCartTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(el => {
      const v = parseFloat((el.innerText || '').replace('R$','').replace(/\s/g,'').replace(/\./g,'').replace(',','.'));
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
