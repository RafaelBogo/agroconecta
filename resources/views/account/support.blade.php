@extends('layouts.app')

@section('title', 'Suporte')
@section('boxed', true)

@section('content')
  <div class="d-flex flex-column align-items-center text-center">

    <div class="mb-3" aria-hidden="true" style="font-size: 40px; line-height: 1">
      <i class="bi bi-life-preserver"></i>
    </div>

    <h2 class="mb-1">Suporte ao Cliente</h2>
    <p class="text-muted mb-4">Estamos aqui para ajudar. Fale com a gente quando precisar.</p>

    <div class="mx-auto w-100" style="max-width: 600px;">
      <div class="p-4 rounded-4 bg-white shadow-sm">
        <h5 class="mb-3 text-start"><i class="bi bi-envelope-paper me-2"></i>Contato por e-mail</h5>

        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
          <span class="email-text fw-medium" id="supportEmail">suporte.agroconecta@gmail.com</span>

          <div class="d-flex gap-2">
            <a href="mailto:suporte.agroconecta@gmail.com" class="btn btn-outline-success">
              <i class="bi bi-envelope"></i> Enviar e-mail
            </a>
            <button class="btn btn-success" id="copyBtn" type="button" aria-live="polite">
              <i class="bi bi-clipboard"></i> Copiar
            </button>
          </div>
        </div>

        <hr class="my-4">

        <div class="text-start">
          <h6 class="mb-2">Dica</h6>
          <p class="text-muted mb-0">Nosso horário de atendimento é de <strong>segunda a sexta, 9h às 18h</strong> (exceto feriados).</p>
        </div>
      </div>

      <div class="d-flex justify-content-center mt-3">
        <a href="{{ route('minha.conta') }}" class="btn btn-dark">
          <i class="bi bi-arrow-left"></i> Voltar
        </a>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/account.support.css') }}">
@endpush

@push('scripts')
<script>
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
</script>
@endpush
