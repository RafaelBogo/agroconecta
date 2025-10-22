@extends('layouts.app')

@section('title', 'Minha Conta')
@section('boxed', true)

@section('content')
<div class="account-container">

  <div class="options-grid">
    <a href="{{ route('orders.index') }}" class="option-card" aria-label="Meus Pedidos">
      <span class="option-icon"><i class="bi bi-cart"></i></span>
      <h5>Meus Pedidos</h5>
      <p>Veja e acompanhe suas compras.</p>
    </a>

    <a href="{{ route('user.data') }}" class="option-card" aria-label="Meus Dados">
      <span class="option-icon"><i class="bi bi-person"></i></span>
      <h5>Meus Dados</h5>
      <p>Visualize e edite seus dados pessoais.</p>
    </a>

    <a href="{{ route('account.myProducts') }}" class="option-card" aria-label="Meus Produtos">
      <span class="option-icon"><i class="bi bi-box"></i></span>
      <h5>Meus Produtos</h5>
      <p>Gerencie os itens que você vende.</p>
    </a>

    <a href="{{ route('account.myRatings') }}" class="option-card" aria-label="Avaliações">
      <span class="option-icon"><i class="bi bi-star"></i></span>
      <h5>Avaliações</h5>
      <p>Veja e faça avaliações de compras.</p>
    </a>

    <a href="{{ route('seller.mySales') }}" class="option-card" aria-label="Minhas Vendas">
      <span class="option-icon"><i class="bi bi-bag"></i></span>
      <h5>Minhas Vendas</h5>
      <p>Acompanhe vendas e retiradas.</p>
    </a>

    <a href="{{ route('support') }}" class="option-card" aria-label="Suporte">
      <span class="option-icon"><i class="bi bi-headset"></i></span>
      <h5>Suporte</h5>
      <p>Fale com a nossa equipe de ajuda.</p>
    </a>
  </div>

  <a href="{{ route('dashboard') }}" class="btn-voltar">
    <i class="bi bi-arrow-left"></i> Voltar
  </a>
</div>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/minhaConta.css') }}">
@endpush