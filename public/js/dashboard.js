 document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('input[name="product"]');
    const clearBtn = document.getElementById('clearProduct');

    const toggleClear = () => clearBtn.classList.toggle('d-none', !input.value.trim());
    input?.addEventListener('input', toggleClear);
    toggleClear();

    clearBtn?.addEventListener('click', () => {
      input.value = '';
      input.focus();
      toggleClear();
    });
  });
