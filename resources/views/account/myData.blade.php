@extends('layouts.app')

@section('title', 'Meus Dados')
@section('boxed', true)

@section('content')
    <h2>Meus Dados</h2>

    <form action="{{ route('user.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Seu Nome Completo</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Seu Nome Completo" value="{{ $user->name }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ $user->email }}" readonly>
                </div>
                <div class="form-group">
                    <label for="phone">Telefone</label>
                    <input type="text" id="phone" name="phone" class="form-control" placeholder="(XX) XXXXX-XXXX" value="{{ $user->phone }}" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Seu Endereço Completo</label>
                    <textarea id="address" name="address" class="form-control" rows="8" placeholder="Cidade, comunidade/bairro, rua, ponto de referência, cor da casa..." required>{{ $user->address }}</textarea>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <a href="{{ route('minha.conta') }}" class="btn btn-secondary">Voltar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </form>

    <!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sucesso!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Seus dados foram atualizados com sucesso!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ccc;
        padding: 15px;
        font-size: 16px;
        width: 100%;
    }

    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
    }

    .btn-success {
        padding: 10px 30px;
    }

    .btn-secondary {
        padding: 10px 30px;
        background-color: black;
        border: none;
    }
</style>
@endpush

@push('scripts')
@if (session('success'))
<script>
    window.onload = function() {
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    };
</script>
@endif
@endpush
