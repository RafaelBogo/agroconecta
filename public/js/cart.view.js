document.addEventListener('DOMContentLoaded', function () {
  console.log('[cart] script carregado');

  // --- Config vinda do Blade (JSON)
  const cfgEl = document.getElementById('cart-config');
  let CFG = {};
  try { CFG = JSON.parse(cfgEl?.textContent || '{}'); } catch (e) { console.error('cart-config inválido', e); }

  const csrf = (document.querySelector('meta[name="csrf-token"]')?.content || '').trim();

  // --- Bootstrap Modal (opcional)
  const hasBootstrap  = !!(window.bootstrap && bootstrap.Modal);
  const deleteModalEl = document.getElementById('deleteModal');
  const deleteModal   = hasBootstrap && deleteModalEl ? bootstrap.Modal.getOrCreateInstance(deleteModalEl) : null;

  // Limpeza global de backdrop quando qualquer modal fechar
  document.addEventListener('hidden.bs.modal', () => {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
  });

  // ---------------- Remover item ----------------
  let currentItemId = null;

  document.addEventListener('click', async (ev) => {
    const btn = ev.target.closest('.delete-button');
    if (!btn) return;

    currentItemId = btn.getAttribute('data-item-id');

    if (deleteModal) {
      // abre o modal nativo pelo data-bs-toggle da view
      return;
    }

    // fallback sem modal
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
      const r = await fetch(CFG.deleteUrl, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
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

  // ---------------- Finalizar pedido ----------------
  const finalizarBtn  = document.getElementById('finalizarPedido');
  const spinner       = document.getElementById('finalizarSpinner');
  const finalizarText = document.getElementById('finalizarText');

  finalizarBtn?.addEventListener('click', async () => {
    if (finalizarBtn.dataset.loading === '1') return;
    finalizarBtn.dataset.loading = '1';
    if (spinner) spinner.style.display = 'inline-block';
    if (finalizarText) finalizarText.style.display = 'none';
    finalizarBtn.disabled = true;

    try {
      const r   = await fetch(CFG.checkoutUrl, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
      });

      const raw = await r.text();
      let data; try { data = JSON.parse(raw); } catch { data = null; }

      console.log('[checkout] status:', r.status);
      console.log('[checkout] raw   :', raw);

      if (!r.ok) {
        const msg = (data && (data.mp_error || data.error || data.message)) || raw || `HTTP ${r.status}`;
        throw new Error(`Falha no checkout: ${msg}`);
      }

      const prefId = data?.preference_id ?? data?.id;
      if (!prefId) throw new Error('Resposta do servidor veio sem preference_id.');

      await ensureMercadoPagoLoaded();
      const pk = CFG.mpPublicKey;
      if (!pk) throw new Error('PUBLIC KEY do Mercado Pago não configurada.');

      const mp = new MercadoPago(pk, { locale: 'pt-BR' });
      mp.checkout({ preference: { id: prefId }, autoOpen: true });
    } catch (e) {
      console.error('[checkout] erro:', e);
      alert(e?.message || 'Houve um erro ao finalizar o pedido.');
    } finally {
      if (spinner) spinner.style.display = 'none';
      if (finalizarText) finalizarText.style.display = 'inline-block';
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

  // ---------------- Quantidade (+/-) ----------------
  document.addEventListener('click', function (ev) {
    const inc = ev.target.closest('.btn-increase');
    const dec = ev.target.closest('.btn-decrease');
    if (!inc && !dec) return;

    const row   = (inc || dec).closest('tr');
    if (!row) return;

    const itemId = row.getAttribute('data-item-id');
    const input  = row.querySelector('.quantity-input');
    let q        = parseFloat(String(input.value).replace(',', '.')) || 0;

    const step = parseFloat(input.getAttribute('step') || '1');
    const min  = parseFloat(input.getAttribute('min')  || String(step));

    if (inc) q = +(q + step).toFixed(2);
    if (dec) q = Math.max(min, +(q - step).toFixed(2));

    input.value = q.toFixed(2);

    updateSubtotal(row, q);

    fetch(`${CFG.updateUrlBase}/${itemId}`, {
      method: 'PUT',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({ quantity: q })
    })
    .then(r => r.ok ? r.json() : Promise.reject(r))
    .then(d => { if (!d?.success) alert(d?.message || 'Erro ao atualizar o carrinho.'); })
    .catch(() => alert('Erro ao atualizar o carrinho. Verifique sua conexão.'));
  });

  // ---------------- Helpers de totais ----------------
  function updateSubtotal(row, quantity) {
    const priceTxt = row.querySelector('td:nth-child(2)')?.innerText || 'R$ 0,00';
    const price    = parseFloat(priceTxt.replace('R$','').replace(/\s/g,'').replace(/\./g,'').replace(',','.')) || 0;
    const sub      = (price * quantity).toFixed(2);
    const subEl    = row.querySelector('.subtotal');
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
