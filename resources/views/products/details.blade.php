@extends('layouts.app')

@section('title', $product->name)
@section('boxed', true)




@push('styles')
<style>
    .product-wrap{
        display:grid;
        grid-template-columns: 1.1fr 1fr;
        gap:32px;
    }
    @media (max-width: 992px){
        .product-wrap{ grid-template-columns: 1fr; }
    }

    .product-image img{
        width:100%;
        height:auto;
        border-radius:16px;
        box-shadow:0 6px 16px rgba(0,0,0,.12);
    }

    .product-title{ font-size:2rem; font-weight:700; color:#1f2937; }
    .product-price{ font-size:1.75rem; font-weight:700; color:#198754; }

    .action-row{
        display:flex; gap:12px; flex-wrap:wrap;
    }
    .action-row .btn{ border-radius:12px; padding:.65rem 1rem; }

    .reviews h4{ font-weight:700; color:#111827; }
    .review-item{
        background:#fff;
        border:1px solid rgba(0,0,0,.065);
        border-radius:14px;
        padding:14px 16px;
        box-shadow:0 4px 12px rgba(17,24,39,.06);
    }
    .reviews-scroll{ max-height:340px; overflow:auto; padding-right:6px; }
    .reviews-scroll::-webkit-scrollbar{ width:10px; }
    .reviews-scroll::-webkit-scrollbar-thumb{ background:rgba(0,0,0,.15); border-radius:10px; }

    .description h4{ font-weight:700; color:#111827; }
    .description p{ color:#374151; }

    .additional-info{ margin-top:24px; }
    .additional-info h4{ font-weight:700; color:#111827; margin-bottom:14px; }

    .info-grid{
        display:grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap:14px;
    }
    @media (max-width: 768px){
        .info-grid{ grid-template-columns: 1fr; }
    }

    .info-card{
        display:flex; align-items:flex-start; gap:12px;
        background:#ffffff;
        border:1px solid rgba(25,135,84,.25);
        border-radius:16px;
        padding:14px 16px;
        box-shadow: 0 6px 16px rgba(25,135,84,.08);
    }
    .info-card .icon{
        display:flex; align-items:center; justify-content:center;
        width:40px; height:40px; border-radius:12px;
        background: rgba(25,135,84,.10);
        flex: 0 0 40px;
    }
    .info-card .icon .bi{
        font-size:1.1rem; color:#198754;
    }
    .info-card .label{
        font-size:.85rem; font-weight:600; color:#065f46; text-transform:uppercase; letter-spacing:.02em;
    }
    .info-card .value{
        font-size:1rem; color:#111827; margin:0;
    }

    .btn:focus-visible, a:focus-visible{
        outline:2px solid rgba(25,135,84,.45);
        outline-offset:2px;
    }
</style>
@endpush


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

        {{-- Ações --}}
        <form id="add-to-cart-form" class="mb-3">
            <label for="quantity" class="form-label">Quantidade</label>
            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" required>

            <div class="action-row mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-cart-plus me-1"></i> Adicionar ao Carrinho
                </button>

                <a href="{{ route('products.show') }}" class="btn btn-outline-dark">
                    <i class="bi bi-bag me-1"></i> Continuar Comprando
                </a>

                <a href="{{ route('chat.with', $product->user_id) }}" class="btn btn-outline-success">
                    <i class="bi bi-chat-dots me-1"></i> Conversar com o Vendedor
                </a>
            </div>
        </form>

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
  const form = document.getElementById('add-to-cart-form');
  const modalEl = document.getElementById('successModal');
  const successModal = bootstrap.Modal.getOrCreateInstance(modalEl);

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const quantity = document.getElementById('quantity').value;

    try {
      const r = await fetch("{{ route('cart.add') }}", {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ product_id: {{ $product->id }}, quantity })
      });

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
</script>
@endpush


