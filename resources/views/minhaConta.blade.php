@extends('layouts.app')

@section('title', 'Minha Conta')
@section('boxed', true)

@push('styles')
<style>
  .account-container{
    display:flex;
    flex-direction:column;
    gap:22px;
  }

  .options-grid{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    width: 100%;
   }

  .option-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 10px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(8px);
    border-radius: 14px;
    padding: 18px 14px;
    text-decoration: none;
    color: #111827;
    border: 1px solid rgba(255, 255, 255, 0.4);
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
  }

  .option-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    border-color: rgba(25, 135, 84, 0.4);
  }

  .option-icon {
    width: 56px;
    height: 56px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: linear-gradient(180deg, #e8f5ee, #d5efe3);
    border: 1px solid rgba(25, 135, 84, 0.25);
  }

  .option-icon i {
    font-size: 1.6rem;
    color: #198754;
  }

  .option-card h5 {
    font-weight: 700;
    font-size: 1.05rem;
    margin: 4px 0 0;
    color: #0f172a;
  }

  .option-card p {
    margin: 0;
    font-size: 0.92rem;
    color: #374151;
  }

  .btn-voltar{
    align-self:flex-start;
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 12px;
    border-radius:10px;
    text-decoration:none;
    background:#fff;
    color:#111827;
    border:1px solid rgba(0,0,0,.12);
    transition:background .15s ease, transform .15s ease, box-shadow .15s ease;
  }

  .btn-voltar:hover{
    background:#f7f9fa;
    transform:translateY(-1px);
    box-shadow:0 6px 16px rgba(17,24,39,.08);
  }

  @media (max-width: 420px){
    .option-card{ padding:16px 12px; }
    .option-icon{ width:52px; height:52px; }
  }
</style>
@endpush

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
