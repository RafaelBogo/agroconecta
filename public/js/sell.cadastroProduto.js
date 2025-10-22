  document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('sellTipsModal');
    if (!modalEl) return;

    // move o modal para fora da .content-box (evita overlay travado)
    document.body.appendChild(modalEl);

    const shouldShow = !localStorage.getItem('sellTipsSeen');
    if (shouldShow) {
      const tipsModal = new bootstrap.Modal(modalEl, {
        backdrop: true,
        keyboard: true
      });
      tipsModal.show();

      // marca como visto ao fechar
      modalEl.addEventListener('hidden.bs.modal', () => {
        localStorage.setItem('sellTipsSeen', '1');
        // foca no primeiro campo do formulário
        const first = document.querySelector('form input, form textarea, form select');
        if (first) first.focus();
      });
    }
  });

