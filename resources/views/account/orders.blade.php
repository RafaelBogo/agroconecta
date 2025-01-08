<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Meus Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset("images/background2.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #787b7b;
            opacity: 0.9;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            text-decoration: underline;
            color: #ccc;
        }

        .orders-container {
            width: 1000px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-height: 500px; /* Altura máxima */
            overflow-y: auto; /* Barra de rolagem */
        }

        /* Barra de rolagem personalizada */
        .orders-container::-webkit-scrollbar {
            width: 35px;
        }

        .orders-container::-webkit-scrollbar-track {
            background: rgba(245, 245, 245, 0.9);
            border-radius: 20px;
        }

        .orders-container::-webkit-scrollbar-thumb {
            background-color: rgba(120, 120, 120, 0.6);
            border-radius: 20px;
        }

        .orders-container::-webkit-scrollbar-thumb:hover {
            background-color: rgba(100, 100, 100, 0.9);
        }

        .order-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .order-item img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            margin-right: 15px;
        }

        .order-actions button {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">AgroConecta</a>
            <div class="navbar-nav mx-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">Início</a>
                <a class="nav-link" href="{{ route('products.show') }}">Produtos</a>
                <a class="nav-link" href="{{ route('sell.important') }}">Vender</a>
                <a class="nav-link" href="{{ route('cart.view') }}">Carrinho</a>
            </div>
            <div class="d-flex align-items-center">
                <a class="nav-link px-3" href="{{ route('minha.conta') }}">Minha Conta</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
                </form>
            </div>
        </div>
    </nav>

    <div class="orders-container">
        @forelse ($orders as $order)
            <div class="order-item">
                <img src="{{ asset('storage/' . $order->product->photo) }}" alt="{{ $order->product->name }}">
                <div>
                    <p><strong>Produto:</strong> {{ $order->product->name }}</p>
                    <p><strong>Preço Unitário:</strong> R$ {{ number_format($order->product->price, 2, ',', '.') }}</p>
                    <p><strong>Total do Pedido:</strong> R$ {{ number_format($order->total_price, 2, ',', '.') }}</p>
                    <p><strong>Quantidade:</strong> {{ $order->quantity }}</p>
                    <p><strong>Status:</strong> {{ $order->status }}</p>
                </div>
                @if ($order->status === 'Processando')
                    <div class="ms-auto order-actions">
                        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Retirado">
                            <button type="submit" class="btn btn-success btn-sm">Marcar como Retirado</button>
                        </form>
                        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Cancelado">
                            <button type="submit" class="btn btn-danger btn-sm">Cancelar Pedido</button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-center">Você ainda não realizou nenhum pedido.</p>
        @endforelse
        <div class="text-center mt-4">
            <a href="{{ route('minha.conta') }}" class="btn btn-dark">Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
