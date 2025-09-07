@extends('layouts.app')

@section('title', 'Editar Produto')
@section('boxed', true)

@section('content')
    <div class="left-section">
        <h4>Editar Produto</h4>
        <p>Aqui você altera informações sobre o seu produto</p>
        <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="product-image">
    </div>
    <div class="right-section">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <input type="text" name="name" class="form-control" placeholder="Nome do Produto" value="{{ $product->name }}" required>
            <textarea name="description" class="form-control" placeholder="Descrição do Produto" rows="4" required>{{ $product->description }}</textarea>
            <input type="number" name="price" class="form-control" placeholder="Valor em Reais" value="{{ $product->price }}" step="0.01" required>
            <input type="text" name="city" class="form-control" placeholder="Cidade" value="{{ $product->city }}">
            <input type="number" name="stock" class="form-control" placeholder="Estoque Disponível" value="{{ $product->stock }}" required>
            <input type="date" name="validity" class="form-control" placeholder="Validade do Produto" value="{{ $product->validity }}">
            <input type="text" name="unit" class="form-control" placeholder="Unidade de Medida (Ex: Unidade, Kg)" value="{{ $product->unit }}">
            <input type="text" name="contact" class="form-control" placeholder="Telefone para Contato" value="{{ $product->contact }}">
            <textarea name="address" class="form-control" placeholder="Endereço Completo" rows="4">{{ $product->address }}</textarea>

            <div class="btn-container">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{ route('account.myProducts') }}" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>

    <!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sucesso!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    O produto foi atualizado com sucesso!
                </div>
                <div class="modal-footer">
                    <a href="{{ route('account.myProducts') }}" class="btn btn-success">OK</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .left-section {
        flex: 1;
        padding-right: 20px;
    }

    .right-section {
        flex: 2;
        display: flex;
        flex-direction: column;
    }

    .form-control {
        border-radius: 10px;
        margin-bottom: 15px;
        padding: 15px;
    }

    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .product-image {
        width: 100%;
        max-width: 250px;
        height: auto;
        border-radius: 10px;
        margin: 0 auto;
        position: relative;
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-success, .btn-secondary {
        padding: 10px 30px;
    }

    .btn-secondary {
        background-color: black;
        border: none;
    }
</style>
@endpush

@push('scripts')
<script>
    @if (session('success'))
        window.onload = function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        };
    @endif
</script>
@endpush
