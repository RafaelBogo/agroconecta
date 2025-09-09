@extends('layouts.app')

@section('title', 'Meus Produtos')
@section('boxed', true)

@section('content')
    <h2>Meus Produtos</h2>

    @if ($products->isEmpty())
        <p class="text-center text-muted">Você ainda não tem produtos registrados.</p>
    @else
        @foreach ($products as $product)
            <div class="product-item">
                <div class="product-info">
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}">
                    <div>
                        <h5>{{ $product->name }}</h5>
                        <p>R$ {{ number_format($product->price, 2, ',', '.') }} por unidade</p>
                    </div>
                </div>
                <div class="btn-container">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn-edit">Editar</a>
                    <button type="button" class="btn-delete delete-button" data-id="{{ $product->id }}">Deletar</button>
                </div>
            </div>
        @endforeach
    @endif

    <a href="{{ route('minha.conta') }}" class="btn-voltar">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

@endsection

@push('modals')
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Deletar Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">Tem certeza que deseja deletar este produto?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-danger confirm-delete">Deletar</button>
      </div>
    </div>
  </div>
</div>
@endpush


@push('styles')
<style>
    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
    }

    .product-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }

    .product-info {
        display: flex;
        align-items: center;
    }

    .product-info img {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        margin-right: 15px;
    }

    .btn-container {
        display: flex;
        gap: 10px;
    }

    .btn-edit {
        background-color: #007bff;
        border: none;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .btn-edit:hover {
        background-color: #0056b3;
    }

    .btn-delete {
        background-color: #dc3545;
        border: none;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .btn-delete:hover {
        background-color: #a71d2a;
    }

    .btn-secondary {
        padding: 10px 30px;
        background-color: black;
        border: none;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script>
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';


  const delModalEl = document.getElementById('deleteModal');
  const delModal   = bootstrap.Modal.getOrCreateInstance(delModalEl);

  document.addEventListener('hidden.bs.modal', () => {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
  });

 
  let currentId = null;
  let currentRow = null;
  let currentUrl = null;


  document.addEventListener('click', (ev) => {
    const btn = ev.target.closest('.delete-button');
    if (!btn) return;

    currentId  = btn.getAttribute('data-id');
    currentRow = btn.closest('.product-item');
    currentUrl = btn.getAttribute('data-delete-url') || `/products/${currentId}`;

    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');

    delModal.show();
  });

  document.querySelector('.confirm-delete')?.addEventListener('click', async () => {
    if (!currentUrl) return;
    try {
      const r = await fetch(currentUrl, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
      });
      if (!r.ok) throw new Error('Falha ao deletar');
      try { await r.json(); } catch(_) {}

      if (currentRow) currentRow.remove();
      delModal.hide();
    } catch (e) {
      console.error(e);
      alert('Ocorreu um erro ao deletar o produto.');
    } finally {
      currentId = null; currentRow = null; currentUrl = null;
    }
  });
</script>
@endpush
