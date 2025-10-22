  document.addEventListener('DOMContentLoaded', () => {
    const copyBtn = document.getElementById('copyBtn');
    const emailEl = document.getElementById('supportEmail');

    copyBtn?.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(emailEl.textContent.trim());
        const original = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="bi bi-clipboard-check"></i> Copiado!';
        copyBtn.classList.remove('btn-success');
        copyBtn.classList.add('btn-outline-success');
        setTimeout(() => {
          copyBtn.innerHTML = original;
          copyBtn.classList.remove('btn-outline-success');
          copyBtn.classList.add('btn-success');
        }, 1800);
      } catch (e) {
        alert('Não foi possível copiar o e-mail. Tente novamente.');
      }
    });
  });
