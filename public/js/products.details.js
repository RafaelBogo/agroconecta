(function () {
  const form    = document.getElementById('add-to-cart-form');
  const modalEl = document.getElementById('successModal');

  if (!form) return; // página sem o form (ex.: vendedor é o dono)

  const cartAddUrl = form.dataset.cartAddUrl || '';
  const loginUrl   = form.dataset.loginUrl   || '';
  const productId  = Number(form.dataset.productId || 0);

  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const csrf     = csrfMeta ? csrfMeta.content : '';

  const successModal = modalEl ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const qtyInput = document.getElementById('quantity');
    let q = (qtyInput?.value || '').trim().replace(',', '.');
    const quantity = parseFloat(q);

    if (!Number.isFinite(quantity) || quantity <= 0) {
      alert('Quantidade inválida.');
      return;
    }

    try {
      const r = await fetch(cartAddUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': csrf,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId, quantity })
      });

      if (r.status === 401) {
        window.location.href = loginUrl;
        return;
      }
      if (!r.ok) throw new Error('Erro ao adicionar ao carrinho');

      // ignora corpo vazio sem quebrar
      try { await r.json(); } catch (_) {}

      // (opcional) limpeza de backdrop caso tenha algum resquício
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('padding-right');

      if (successModal) successModal.show();
    } catch (err) {
      console.error(err);
      alert('Houve um erro ao adicionar o produto ao carrinho.');
    }
  });

  if (modalEl) {
    modalEl.addEventListener('hidden.bs.modal', () => {
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('padding-right');
    });
  }
})();
