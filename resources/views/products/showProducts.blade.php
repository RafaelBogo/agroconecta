@extends('layouts.app')

@section('title', 'Produtos')
{{-- se quiser a caixa branca padrão, descomente a linha abaixo --}}
{{-- @section('boxed', true) --}}

@section('content')
  <div class="container text-center">

    <div class="search-bar">
      <form action="{{ route('products.search') }}" method="GET" class="d-flex">
        <input type="text" name="product" placeholder="Busque por um Produto"
               class="form-control me-2 flex-grow-2" value="{{ request('product') }}">
        <select name="city" class="form-select me-2 flex-grow-1" style="max-width: 150px;">
          <option value="">Cidade</option>
          @foreach($cities as $city)
            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-success">Buscar</button>
      </form>
    </div>

    <div class="products-container">
      @if($products->isEmpty())
        <p class="text-muted mb-0">Nenhum produto encontrado.</p>
      @else
        <div class="row g-3">
          @foreach($products as $product)
            <div class="col-12 col-sm-6 col-lg-4">
              <a href="{{ route('products.details', $product->id) }}" class="text-decoration-none text-reset">
                <div class="card product-card h-100">
                  <img
                    src="{{ asset('storage/' . $product->photo) }}"
                    class="card-img-top product-image"
                    alt="{{ $product->name }}"
                  >
                  <div class="card-body">
                    <h5 class="card-title mb-1">{{ $product->name }}</h5>
                    <p class="mb-1"><strong>Preço:</strong> R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                    <small class="text-muted"><strong>Disponível em:</strong> {{ $product->city }}</small>
                  </div>
                </div>
              </a>
            </div>
          @endforeach
        </div>
      @endif
    </div>

  </div>
@endsection

@push('styles')
<style>
  /* Usa o fundo do layout; aqui só estilizamos a página */
  .search-bar{
    max-width: 900px;
    margin: 50px auto 20px;
    padding: 20px;
    background: rgba(255,255,255,0.92);
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.08);
  }

  .products-container{
    background: rgba(255,255,255,0.92);
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.12);
    margin: 0 auto 40px;
    max-width: 1200px;
  }

  .product-card{
    transition: transform .2s ease, box-shadow .2s ease;
  }
  .product-card:hover{
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
  }

  .product-image{
    width: 100%;
    height: 180px;         /* antes estava "height: px;" */
    object-fit: cover;
    border-top-left-radius: .5rem;
    border-top-right-radius: .5rem;
  }
</style>
@endpush
