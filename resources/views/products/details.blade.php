@extends('layouts.app')

@section('title', $product->name)
@section('boxed', true)

@php
  $decimais = in_array(strtolower($product->unit), ['kg','g','l','ml']);
  $isOwner  = auth()->check() && auth()->id() === $product->user_id;
@endphp

@section('content')
<div class="product-wrap">
  {{-- Coluna Esquerda: Imagem e avaliação --}}
  <div>
    <div class="product-image mb-4">
      <img src="{{ asset('storage/' . $product->photo) }}" alt="Foto do produto {{ $product->name }}">
    </div>

    {{-- Avaliações --}}
    <div class="reviews">
      <h4 class="mb-3">Avaliações dos Clientes</h4>
      <div class="reviews-scroll">
        @forelse($product->reviews as $review)
          <div class="review-item mb-3">
            <div class="d-flex justify-content-between flex-wrap">
              <strong>{{ $review->user->name }}</strong>
              <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
            </div>
            <div class="mt-1 text-warning" aria-label="Nota: {{ $review->rating }} de 5">
              @for ($i = 1; $i <= 5; $i++)
                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>
              @endfor
              <span class="visually-hidden">Avaliação {{ $review->rating }} de 5</span>
            </div>
            <p class="mt-2 mb-0">{{ $review->comment }}</p>
          </div>
        @empty
          <div class="text-center text-muted">Ainda não há avaliações para este produto.</div>
        @endforelse
      </div>
    </div>
  </div>

  {{-- Coluna Direita: Detalhes e ações --}}
  <div>
    <h1 class="product-title">{{ $product->name }}</h1>
    <div class="product-price mb-3">R$ {{ number_format($product->price, 2, ',', '.') }}</div>

    @if($isOwner)
      <div class="alert alert-info mb-3">
        Você é o vendedor deste produto. Ações de compra e conversa estão desabilitadas.
      </div>

      <div class="action-row">
        <a href="{{ route('products.show') }}" class="btn btn-outline-dark">
          <i class="bi bi-bag me-1"></i> Continuar comprando
        </a>
      </div>
    @else
      {{-- Ações --}}
      <form id="add-to-cart-form" class="mb-3">
        <label for="quantity" class="form-label">Quantidade</label>
        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="{{ $decimais ? '0.01' : '1' }}" step="{{ $decimais ? '0.01' : '1' }}" inputmode="decimal" required>

        <div class="action-row mt-3">
          <button type="submit" class="btn btn-success">
            <i class="bi bi-cart-plus me-1"></i> Adicionar ao Carrinho
          </button>

          <a href="{{ route('products.show') }}" class="btn btn-outline-dark">
            <i class="bi bi-bag me-1"></i> Continuar comprando
          </a>

          <a href="{{ route('chat.with', $product->user_id) }}" class="btn btn-outline-success">
            <i class="bi bi-chat-dots me-1"></i> Conversar com o vendedor
          </a>
        </div>
      </form>
    @endif

    <div class="description mt-4">
      <h4>Descrição</h4>
      <p>{{ $product->description }}</p>
    </div>

    <div class="additional-info">
      <h4>Informações Adicionais</h4>
      <div class="info-grid">
        <div class="info-card">
          <div class="icon"><i class="bi bi-calendar-event"></i></div>
          <div>
            <div class="label">Validade</div>
            <p class="value mb-0">{{ $product->validity }}</p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-basket"></i></div>
          <div>
            <div class="label">Unidade</div>
            <p class="value mb-0">{{ $product->unit }}</p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-telephone"></i></div>
          <div>
            <div class="label">Contato</div>
            <p class="value mb-0">{{ $product->contact }}</p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-geo-alt"></i></div>
          <div>
            <div class="label">Endereço</div>
            <p class="value mb-0">{{ $product->address }}, {{ $product->city }}</p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-person-badge"></i></div>
          <div>
            <div class="label">Vendedor</div>
            <p class="value mb-0">{{ $product->user->name ?? '—' }}</p>
          </div>
        </div>

        <div class="info-card">
          <div class="icon"><i class="bi bi-box-seam"></i></div>
          <div>
            <div class="label">Estoque Disponível</div>
            <p class="value mb-0">{{ $product->stock }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal de Sucesso --}}
@push('modals')
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Sucesso!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">Produto adicionado ao carrinho com sucesso!</div>
      <div class="modal-footer">
        <a href="{{ route('cart.view') }}" class="btn btn-success">Ir para o carrinho</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
@endpush

@endsection

@push('scripts')
<script>
(function() {
  const form = document.getElementById('add-to-cart-form');
  const modalEl = document.getElementById('successModal');

  if (!form) return;

  const successModal = bootstrap.Modal.getOrCreateInstance(modalEl);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    let q = (document.getElementById('quantity').value || '').trim().replace(',', '.');
    const quantity = parseFloat(q);
    if (!Number.isFinite(quantity) || quantity <= 0) {
      alert('Quantidade inválida.');
      return;
    }

    try {
      const r = await fetch("{{ route('cart.add') }}", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ product_id: {{ $product->id }}, quantity })
      });

      if (r.status === 401) {
        window.location.href = "{{ route('login') }}";
        return;
      }

      if (!r.ok) throw new Error('Erro ao adicionar ao carrinho');

      try { await r.json(); } catch (_) {}

      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('padding-right');

      successModal.show();
    } catch (err) {
      console.error(err);
      alert('Houve um erro ao adicionar o produto ao carrinho.');
    }
  });

  modalEl.addEventListener('hidden.bs.modal', () => {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
  });
})();
</script>
@endpush

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/products.details.css') }}">
@endpush
