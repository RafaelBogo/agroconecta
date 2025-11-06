@extends('layouts.app')

@section('title', 'Minhas Avaliações')
@section('boxed', true)
@section('back', route('myAccount'))

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
                            <img src="{{ $foto }}" alt="{{ $product->name }}" class="thumb"
                                onerror="this.onerror=null;this.src='https://via.placeholder.com/120x120?text=Sem+imagem';">
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
                @if(in_array($product->id, $eligibleIds ?? [], true))
                    <form action="{{ route('products.reviews.store', $product->id) }}" method="POST" class="mt-3 rating-form"
                        id="form-{{ $product->id }}">
                        @csrf
                    </form>
                @else
                    <div class="mt-3">
                        <span class="badge text-bg-secondary px-3 py-2">
                            <i class="bi bi-clock me-1"></i> Aguardando retirada
                        </span>
                    </div>
                @endif
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
