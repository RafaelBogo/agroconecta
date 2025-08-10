@extends('layouts.app')

@section('title', 'Minhas Avaliações')
@section('boxed', true)

@section('content')
    <h3 class="mb-4 text-center">Avaliar Produtos Comprados</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($products as $product)
        <div class="product-item mb-3 p-3 border rounded">
            <strong>{{ $product->name }}</strong><br>
            <small>{{ $product->description }}</small><br>

            @if(in_array($product->id, $reviews))
                <span class="text-success">Você já avaliou este produto.</span>
            @else
                <form action="{{ route('products.reviews.store', $product->id) }}" method="POST" class="mt-2">
                    @csrf
                    <div class="mb-2">
                        <label for="rating" class="form-label">Nota (1 a 5):</label>
                        <input type="number" name="rating" class="form-control" min="1" max="5" required>
                    </div>
                    <div class="mb-2">
                        <label for="comment" class="form-label">Comentário:</label>
                        <textarea name="comment" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
                </form>
            @endif
        </div>
    @empty
        <div class="text-center">Você ainda não comprou nenhum produto.</div>
    @endforelse
    <a href="{{ route('minha.conta') }}" class="btn-voltar">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

@endsection

@push('styles')
<style>
    .ratings-box {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        width: 100%;
        margin: 0 auto 50px;
    }

    .product-item {
        border: 1px solid #ccc;
        border-radius: 20px;
        padding: 15px;
        margin-bottom: 15px;
        background-color: #fff;
    }

    .btn-dark {
        margin-top: 30px;
        color: white;
        width: 100%;
        font-size: 1.2rem;
        padding: 10px;
    }

    .ratings-box::-webkit-scrollbar {
        width: 35px;
    }

    .ratings-box::-webkit-scrollbar-track {
        background: rgba(245, 245, 245, 0.9);
        border-radius: 20px;
    }

    .ratings-box::-webkit-scrollbar-thumb {
        background-color: rgba(120, 120, 120, 0.6);
        border-radius: 20px;
    }

    .ratings-box::-webkit-scrollbar-thumb:hover {
        background-color: rgba(100, 100, 100, 0.9);
    }
</style>
@endpush
