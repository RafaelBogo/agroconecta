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
      <h5 class="name m-0">
        {{ $product->name }}
        @unless($product->is_active)
          <span class="badge bg-secondary ms-2">Inativo</span>
        @endunless
      </h5>


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

      <form action="{{ route('products.toggleActive', $product->id) }}" method="POST" class="d-inline">
        @csrf
          <button class="btn btn-sm btn-{{ $product->is_active ? 'warning' : 'success' }}">
        @if($product->is_active)
          <i class="bi bi-pause-circle me-1"></i> Inativar
        @else
          <i class="bi bi-play-circle me-1"></i> Ativar
        @endif
        </button>
      </form>


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

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/account.myProducts.css') }}">
@endpush
