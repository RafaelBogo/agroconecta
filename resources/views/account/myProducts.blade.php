@extends('layouts.app')

@section('title', 'Meus Produtos')
@section('boxed', true)

@section('content')
    <h2>Meus Produtos</h2>

    @if ($products->isEmpty())
      <div class="empty-state text-center py-5">
          <img src="{{ asset('images/empty-box.svg') }}" alt="" class="empty-illust mb-3">
          <h5 class="mb-1">Você ainda não cadastrou produtos</h5>
          <p class="text-muted mb-3">Que tal começar agora?</p>
        <a href="{{ route('sell.store') }}" class="btn btn-success">
          <i class="bi bi-plus-lg me-1"></i> Cadastrar produto
        </a>
      </div>
    @else
      <div class="header-row d-flex align-items-center justify-content-between mb-3">
         <h2 class="m-0">Produtos em Venda: <span class="count">({{ $products->count() }})</span></h2>
        <a href="{{ route('sell.store') }}" class="btn btn-success">
          <i class="bi bi-plus-lg me-1"></i> Novo
         </a>
      </div>

  <div class="products-grid">
    @foreach ($products as $product)
      <div class="product-card">
        <div class="thumb">
          <img loading="lazy" src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}">
      </div>

    <div class="middle">
      <h5 class="name m-0">{{ $product->name }}</h5>

    <div class="meta">
      @if(!is_null($product->stock))
        <span class="badge rounded-pill bg-light text-dark">
          <i class="bi bi-box-seam me-1"></i> {{ $product->stock }} em estoque
        </span>
      @endif
    </div>
  </div>

  <div class="actions">
    <div class="price">R$ {{ number_format($product->price, 2, ',', '.') }}
      @if(!empty($product->unit)) <small class="text-muted">/ {{ $product->unit }}</small>@endif
    </div>

    <div class="btns">
      <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-pencil-square me-1"></i> Editar
      </a>
      <button type="button" class="btn btn-sm btn-outline-danger delete-button" data-id="{{ $product->id }}">
        <i class="bi bi-trash3 me-1"></i> Deletar
      </button>
    </div>
  </div>
</div>
    @endforeach
  </div>
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
  h2{ font-weight:800; text-align:center; }

  .products-grid{ display:grid; grid-template-columns:1fr; gap:14px; }

  .product-card{
    display:flex; align-items:center; gap:16px;
    background: rgba(255,255,255,.92);
    border:1px solid rgba(255,255,255,.6);
    border-radius:14px; padding:14px 16px;
    box-shadow:0 10px 24px rgba(0,0,0,.08);
  }

  .thumb{ flex:0 0 64px; }
  .thumb img{ width:64px; height:64px; object-fit:cover; border-radius:10px; }

  .middle{ flex:1; min-width:0; }
  .name{ font-weight:700; color:#1f2937; margin-bottom:6px; }
  .meta{ display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
  .meta .badge{ background:#f5f6f8 !important; }

  .actions{
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:8px; min-width:220px; text-align:center;
  }
  .actions .price{
    font-weight:800; color:#0f5132;
  }
  .actions .btns{ display:flex; gap:8px; }

  .btn-voltar{ margin-top: 18px; }
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
