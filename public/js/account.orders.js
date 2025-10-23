(function(){
  const timersMap = new Map(); 

  function fmt(t){
    const m = Math.floor(t/60); const s = t%60;
    return `${m}m ${String(s).padStart(2,'0')}s`;
  }

 
  function startCountdown(){
    document.querySelectorAll('.cancel-timer').forEach(timer => {
      const id = timer.id || `timer-${Math.random()}`;
      timer.id = id;

      const status    = timer.getAttribute('data-status');
      const expiresIso = timer.getAttribute('data-expires-at'); 
      const spanVal   = timer.querySelector('span') || timer;

 
      if (status !== 'Processando' || !expiresIso) {
        timer.textContent = 'Não aplicável';
        timer.classList.remove('timer-pill');
        if (timersMap.has(id)) { clearInterval(timersMap.get(id)); timersMap.delete(id); }
        return;
      }

      if (timersMap.has(id)) { clearInterval(timersMap.get(id)); timersMap.delete(id); }

      function getTimeLeft() {
        const expiresAt = new Date(expiresIso).getTime(); // ms
        const now       = Date.now();
        return Math.max(0, Math.floor((expiresAt - now) / 1000));
      }

      let timeLeft = getTimeLeft();
      if (timeLeft <= 0) {
        spanVal.textContent = 'Tempo para cancelamento expirado.';
        timer.closest('.order-card')?.querySelector('.cancel-button')?.remove();
        return;
      }

  
      spanVal.textContent = fmt(timeLeft);

      const iv = setInterval(() => {
        timeLeft = getTimeLeft();
        if (timeLeft > 0) {
          spanVal.textContent = fmt(timeLeft);
        } else {
          clearInterval(iv);
          timersMap.delete(id);
          spanVal.textContent = 'Tempo para cancelamento expirado.';
          timer.closest('.order-card')?.querySelector('.cancel-button')?.remove();
        }
      }, 1000);

      timersMap.set(id, iv);
    });
  }


  document.addEventListener('DOMContentLoaded', function(){
    startCountdown();
    setupCancelButtons();
  });
})();
