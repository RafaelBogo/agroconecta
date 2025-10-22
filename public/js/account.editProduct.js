document.addEventListener('DOMContentLoaded', () => {
  // ----- Alternância do campo "unit_custom"
  const sel  = document.getElementById('unit');
  const cust = document.getElementById('unit_custom');

  function toggleCustom() {
    if (!sel || !cust) return;
    if (sel.value === 'custom') {
      cust.classList.remove('d-none');
    } else {
      cust.classList.add('d-none');
      // só limpa se não foi marcado para manter (caso você use isso em algum momento)
      if (!cust.dataset.keep) cust.value = '';
    }
  }
  if (sel) {
    sel.addEventListener('change', toggleCustom);
    toggleCustom();
  }

  // ----- Modal de sucesso
  const modalEl = document.getElementById('successModal');
  if (modalEl) {
    // Evita problemas de overlay dentro de containers com overflow/transform
    document.body.appendChild(modalEl);

    // Mostra o modal se vier marcado com sucesso na sessão
    const shouldShow = (modalEl.dataset.success || '0') === '1';
    const successModal = bootstrap.Modal.getOrCreateInstance(modalEl);
    if (shouldShow) successModal.show();

    // Limpeza total quando fechar
    modalEl.addEventListener('hidden.bs.modal', () => {
      document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('padding-right');
    });
  }
});
