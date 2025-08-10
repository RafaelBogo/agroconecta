@extends('layouts.app')

@section('title', 'Minhas Vendas')
@section('boxed', true)

@section('content')
    <h1 class="text-center mb-4">Minhas Vendas</h1>

    @if ($vendas->isEmpty())
        <p class="text-center">Você ainda não possui vendas cadastradas.</p>
    @else
        <table class="table table-bordered table-hover mt-4">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Comprador</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendas as $venda)
                    <tr>
                        <td>{{ $venda->product->name }}</td>
                        <td>{{ $venda->user->name }}</td>
                        <td>{{ $venda->quantity }}</td>
                        <td>R$ {{ number_format($venda->total_price, 2, ',', '.') }}</td>
                        <td>{{ $venda->status }}</td>
                        <td>
                            @if ($venda->status === 'Processando')
                                <form action="{{ route('seller.confirmRetirada') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $venda->id }}">
                                    <button type="submit" class="btn btn-success btn-sm">Confirmar Retirada</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('minha.conta') }}" class="btn btn-dark">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>
@endsection
