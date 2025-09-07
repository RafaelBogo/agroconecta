@extends('layouts.app')

@section('title', 'Produtos')
@section('boxed', true)

@section('content')
  {{-- Cabeçalho + busca --}}
  <div class="search-card shadow-sm">
    <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-2 mb-3">
      <div>
        <h2 class="mb-0">Produtos</h2>
        <small class="text-muted">Busque por nome e filtre por cidade</small>
      </div>
      @if(isset($products) && method_exists($products, 'count'))
        <small class="text-muted">{{ $products->count() }} resultado(s)</small>
      @endif
    </div>

    <form action="{{ route('products.search') }}" method="GET" class="row g-2">
      <div class="col-12 col-md-7">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-bag"></i></span>
          <input
            type="text"
            name="product"
            class="form-control"
            placeholder="Ex.: tomate, mel, queijo…"
            value="{{ request('product') }}"
            aria-label="Buscar por produto"
          >
          <button type="button" id="clearProduct" class="btn btn-outline-secondary d-none">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>

      <div class="col-12 col-md-3">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
          <select name="city" class="form-select" aria-label="Filtrar por cidade">
            <option value="">Todas as cidades</option>
            @foreach($cities as $city)
              <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                {{ $city }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-12 col-md-2 d-grid">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-search"></i> Buscar
        </button>
      </div>
    </form>

    @if(request()->filled('product') || request()->filled('city'))
      <div class="mt-2">
        <a href="{{ route('products.search') }}" class="btn btn-link p-0 text-decoration-none">
          Limpar filtros
        </a>
      </div>
    @endif
  </div>

  {{-- Lista de produtos --}}
  <div class="products-card shadow-sm mt-3">
    @if($products->isEmpty())
      <div class="text-center py-5">
        <div class="mb-3" style="font-size:42px;line-height:1"><i class="bi bi-box-seam"></i></div>
        <h5 class="mb-1">Nenhum produto encontrado</h5>
        <p class="text-muted mb-3">Tente ajustar sua busca ou selecionar outra cidade.</p>
        <a class="btn btn-dark" href="{{ route('products.show') }}"><i class="bi bi-arrow-counterclockwise"></i> Ver todos</a>
      </div>
    @else
      <div class="row g-3">
        @foreach($products as $product)
          <div class="col-12 col-sm-6 col-lg-4">
            <a href="{{ route('products.details', $product->id) }}" class="text-decoration-none text-reset">
              <div class="card product-card h-100">
                <div class="ratio ratio-16x9">
                  <img
                    src="{{ asset('storage/' . $product->photo) }}"
                    class="product-image rounded-top"
                    alt="{{ $product->name }}"
                  >
                </div>
                <div class="card-body">
                  <h5 class="card-title mb-1 text-truncate">{{ $product->name }}</h5>
                  <p class="mb-1"><strong>Preço:</strong> R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                  <small class="text-muted d-inline-flex align-items-center gap-1">
                    <i class="bi bi-geo-alt"></i> {{ $product->city }}
                  </small>
                </div>
              </div>
            </a>
          </div>
        @endforeach
      </div>

      @if(method_exists($products, 'links'))
        <div class="d-flex justify-content-center mt-3">
          {{ $products->links() }}
        </div>
      @endif
    @endif
  </div>
@endsection

@push('styles')
<style>
  .search-card{
    max-width: 1100px;
    margin: 0 auto;
    padding: 20px;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(5px);
    border-radius: 16px;
  }
  .products-card{
    max-width: 1200px;
    margin: 0 auto 8px;
    padding: 16px;
    background: rgba(255,255,255,0.92);
    border-radius: 16px;
  }

  .product-card{
    transition: transform .15s ease, box-shadow .15s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,.08);
  }
  .product-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 10px 18px rgba(0,0,0,.15);
  }

  .product-image{
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .input-group .form-control,
  .input-group .form-select { min-height: 42px; }
  .btn { min-height: 42px; }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('input[name="product"]');
    const clearBtn = document.getElementById('clearProduct');

    const toggleClear = () => clearBtn?.classList.toggle('d-none', !input?.value.trim());
    input?.addEventListener('input', toggleClear);
    toggleClear();

    clearBtn?.addEventListener('click', () => {
      input.value = '';
      input.focus();
      toggleClear();
    });
  });
</script>
@endpush
