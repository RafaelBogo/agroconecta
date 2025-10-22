// Texto dinâmico para a nota escolhida
document.querySelectorAll('.rating-form').forEach(function(form){
    const stars = form.querySelectorAll('.rating-stars input');
    const output = form.querySelector('#chosen-' + form.id.replace('form-', '') );

    stars.forEach(radio=>{
        radio.addEventListener('change', ()=>{
            output.textContent = `Nota selecionada: ${radio.value} de 5`;
        });
    });

    // Evitar duplo submit
    form.addEventListener('submit', function(){
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Enviando...';
    });
});
