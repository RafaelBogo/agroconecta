@extends('layouts.app')

@section('title', 'Minhas Avaliações')
@section('boxed', true)
@section('back', route('minha.conta'))

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap mb-3">
    <h3 class="mb-0">Avaliar produtos comprados</h3>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@forelse($products as $product)
    @php
        $raw = (string) ($product->photo ?? '');
        $isUrl = $raw !== '' && (strpos($raw, 'http://') === 0 || strpos($raw, 'https://') === 0);
        $foto = $isUrl ? $raw : route('media', ['path' => ltrim($raw, '/')]);
    @endphp

    <div class="rating-card mb-3">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-md-8">
                <div class="d-flex align-items-start gap-3">
                    @if($raw)
                        <img
                            src="{{ $foto }}"
                            alt="{{ $product->name }}"
                            class="thumb"
                            onerror="this.onerror=null;this.src='https://via.placeholder.com/120x120?text=Sem+imagem';"
                        >
                    @else
                        <div class="thumb thumb-placeholder d-flex align-items-center justify-content-center">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <strong class="product-name">{{ $product->name }}</strong>
                        <p class="product-desc mb-0">{{ $product->description }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 text-md-end">
                @if(in_array($product->id, $reviews))
                    <span class="badge text-bg-success px-3 py-2">
                        <i class="bi bi-check2-circle me-1"></i> Você já avaliou
                    </span>
                @endif
            </div>
        </div>

        @unless(in_array($product->id, $reviews))
            <form action="{{ route('products.reviews.store', $product->id) }}" method="POST" class="mt-3 rating-form" id="form-{{ $product->id }}">
                @csrf

                {{-- Estrelas --}}
                <div class="rating-stars" data-field="rating">
                    <input type="radio" name="rating" id="r5-{{ $product->id }}" value="5" required>
                    <label for="r5-{{ $product->id }}" title="5"><i class="bi bi-star-fill"></i></label>

                    <input type="radio" name="rating" id="r4-{{ $product->id }}" value="4">
                    <label for="r4-{{ $product->id }}" title="4"><i class="bi bi-star-fill"></i></label>

                    <input type="radio" name="rating" id="r3-{{ $product->id }}" value="3">
                    <label for="r3-{{ $product->id }}" title="3"><i class="bi bi-star-fill"></i></label>

                    <input type="radio" name="rating" id="r2-{{ $product->id }}" value="2">
                    <label for="r2-{{ $product->id }}" title="2"><i class="bi bi-star-fill"></i></label>

                    <input type="radio" name="rating" id="r1-{{ $product->id }}" value="1">
                    <label for="r1-{{ $product->id }}" title="1"><i class="bi bi-star-fill"></i></label>
                </div>
                <div class="small text-muted" id="chosen-{{ $product->id }}" aria-live="polite"></div>

                {{-- Comentário --}}
                <div class="mt-3">
                    <label for="comment-{{ $product->id }}" class="form-label fw-semibold">Comentário (opcional)</label>
                    <textarea id="comment-{{ $product->id }}" name="comment" class="form-control" rows="2"
                              placeholder="Conte como foi sua experiência"></textarea>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send me-1"></i> Enviar avaliação
                    </button>
                    <a href="{{ route('products.show') }}" class="btn btn-outline-dark">
                        <i class="bi bi-bag me-1"></i> Continuar comprando
                    </a>
                </div>
            </form>
        @endunless
    </div>
@empty
    <div class="text-center py-4">
        <i class="bi bi-archive text-muted d-block mb-2" style="font-size:2rem;"></i>
        Você ainda não tem produtos para avaliar.
    </div>
@endforelse
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/account.myRatings.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/account.myRatings.js') }}" defer></script>
@endpush
