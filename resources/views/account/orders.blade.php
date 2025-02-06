<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos - AgroConecta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset("images/background2.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding-top: 70px;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            background-color: rgba(120, 123, 123, 0.9);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            padding: 30px;
            margin: 50px auto;
            max-height: 70vh; /* Altura máxima para barra de rolagem */
            overflow-y: auto; /* Barra de rolagem vertical */
        }

        /* Estilo personalizado da barra de rolagem */
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
            border-radius: 10px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-item img {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            margin-right: 15px;
        }

        .order-details {
            flex: 1;
        }

        .order-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .timer {
            font-size: 0.9rem;
            color: red;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .btn-dark {
            color: white;
            padding: 10px 20px;
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
        <h2 class="text-center mb-4">Meus Pedidos</h2>
        @forelse ($orders as $order)
            <div class="order-item">
                <img src="{{ asset('storage/' . $order->product->photo) }}" alt="{{ $order->product->name }}">
                <div class="order-details">
                    <p><strong>Produto:</strong> {{ $order->product->name }}</p>
                    <p><strong>Preço Unitário:</strong> R$ {{ number_format($order->product->price, 2, ',', '.') }}</p>
                    <p><strong>Total:</strong> R$ {{ number_format($order->total_price, 2, ',', '.') }}</p>
                    <p><strong>Quantidade:</strong> {{ $order->quantity }}</p>
                    <p><strong>Status:</strong> {{ $order->status }}</p>
                    @if ($order->cancel_time_left > 0)
                        <p class="timer" data-time-left="{{ $order->cancel_time_left }}">Tempo restante para cancelar: <span></span></p>
                    @endif
                </div>
                @if ($order->status === 'Processando' && $order->cancel_time_left > 0)
                    <div class="order-actions">
                        <form action="{{ route('orders.update', $order->id) }}" method="POST">
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
        <div class="btn-container">
            <a href="{{ route('minha.conta') }}" class="btn btn-dark">Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.timer').forEach(timerElement => {
            const timeLeft = parseInt(timerElement.getAttribute('data-time-left'), 10);
            const span = timerElement.querySelector('span');

            let remainingTime = timeLeft;

            const updateTimer = () => {
                if (remainingTime > 0) {
                    const minutes = Math.floor(remainingTime / 60);
                    const seconds = remainingTime % 60;
                    span.textContent = `${minutes}m ${seconds < 10 ? '0' : ''}${seconds}s`; // Corrige a exibição
                    remainingTime--;
                } else {
                    timerElement.textContent = 'Tempo para cancelamento expirado.';
                    const button = timerElement.closest('.order-item').querySelector('.btn-danger');
                    if (button) button.remove();
                }
            };

            updateTimer();
            setInterval(updateTimer, 1000);
        });

    </script>
</body>
</html>
