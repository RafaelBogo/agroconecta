document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('successModal');
  if (!modalEl) return;

  document.body.appendChild(modalEl);

  const shouldShow = (modalEl.dataset.success || '0') === '1';
  const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
  if (shouldShow) modal.show();

  modalEl.addEventListener('hidden.bs.modal', () => {
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
  });
});
