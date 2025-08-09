{{-- resources/views/account/minhaConta.blade.php --}}
@extends('layouts.app')

@section('title', 'Minha Conta')
@section('boxed', true)

@push('styles')
<style>
    .account-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .options-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        width: 100%;
    }

    .option-card {
        background-color: rgba(255, 255, 255, 0.9);
        transition: transform 0.2s ease-in-out;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        font-size: 1.1rem;
    }

    .option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
    }

    .option-card i {
        font-size: 2.5rem;
        color: #4CAF50;
        margin-bottom: 10px;
    }

    .option-card h5 {
        font-weight: bold;
        color: #4CAF50;
        margin-top: 10px;
    }

    .btn-dark {
        margin-top: 30px;
        color: white;
        width: 100%;
        font-size: 1.2rem;
        padding: 10px;
    }
</style>
@endpush

@section('content')
<div class="account-container">
    <div class="options-grid">
        <div class="option-card">
            <a href="{{ route('orders.index') }}" style="text-decoration: none; color: inherit;">
                <i class="bi bi-cart"></i>
                <h5>Meus Pedidos</h5>
                <p>Verifique os produtos que você comprou.</p>
            </a>
        </div>
        <div class="option-card">
            <a href="{{ route('user.data') }}" style="text-decoration: none; color: inherit;">
                <i class="bi bi-person"></i>
                <h5>Meus Dados</h5>
                <p>Verifique e edite seus dados pessoais.</p>
            </a>
        </div>
        <div class="option-card">
            <a href="{{ route('account.myProducts') }}" style="text-decoration: none; color: inherit;">
                <i class="bi bi-box"></i>
                <h5>Meus Produtos</h5>
                <p>Gerencie e edite os produtos que você vende.</p>
            </a>
        </div>
        <div class="option-card">
            <a href="{{ route('account.myRatings') }}" style="text-decoration: none; color: inherit;">
                <i class="bi bi-star"></i>
                <h5>Avaliações</h5>
                <p>Avalie produtos que você comprou.</p>
            </a>
        </div>
        <div class="option-card">
            <a href="{{ route('seller.mySales') }}" style="text-decoration: none; color: inherit;">
                <i class="bi bi-bag"></i>
                <h5>Minhas Vendas</h5>
                <p>Gerencie as vendas e confirme as retiradas.</p>
            </a>
        </div>
        <div class="option-card">
            <a href="{{ route('support') }}" style="text-decoration: none; color: inherit;">
                <i class="bi bi-headset"></i>
                <h5>Suporte</h5>
                <p>Entre em contato com nosso suporte para obter ajuda.</p>
            </a>
        </div>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-dark">Voltar</a>
</div>
@endsection
