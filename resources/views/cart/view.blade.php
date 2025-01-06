<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meu Carrinho - AgroConecta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset("images/background2.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin: 0;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            background-color: rgba(120, 123, 123, 0.9);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-content {
            display: flex;
            gap: 20px;
        }

        .cart-table {
            flex: 3;
        }

        .cart-summary {
            flex: 1;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-summary h4 {
            font-weight: bold;
        }

        .cart-summary p {
            margin: 10px 0;
            font-size: 1rem;
        }

        .btn-finalize {
            margin-top: 20px;
            width: 100%;
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
                <a class="nav-link" href="#">Carrinho</a>
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

    <div class="cart-container">
    <h1>Meu Carrinho</h1>

    @php $total = 0; @endphp <!-- Inicialize $total aqui, fora de qualquer lógica condicional -->

    <div class="cart-content">
        <div class="cart-table">
            @if (empty($cartItems))
                <p>O carrinho está vazio.</p>
            @else
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $id => $item)
                            @php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $item['photo']) }}" alt="{{ $item['name'] }}" style="width: 50px; height: 50px; border-radius: 5px;">
                                    {{ $item['name'] }}
                                </td>
                                <td>R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                                <td>
                                    <form class="delete-item-form" data-item-id="{{ $id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-button">Remover</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="cart-summary">
            <h4>Resumo</h4>
            <p>Valor dos produtos: <span>R$ {{ number_format($total, 2, ',', '.') }}</span></p>
            <p>Entrega: <span>Retirada com o produtor</span></p>
            <p>Desconto: <span>R$ 0,00</span></p>
            <hr>
            <p><strong>Total: R$ {{ number_format($total, 2, ',', '.') }}</strong></p>
            <button class="btn btn-success btn-finalize">Finalizar Pedido</button>
            <button class="btn btn-secondary btn-finalize">Continuar Comprando</button>
        </div>
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.delete-item-form');
                const itemId = form.getAttribute('data-item-id');

                fetch("{{ route('cart.delete') }}", {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        item_id: itemId
                    })
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Erro ao remover o item do carrinho.');
                })
                .then(data => {
                    if (data.success) {
                        // Remove o item da tabela
                        form.closest('tr').remove();

                        // Atualiza o resumo do carrinho
                        updateCartSummary();
                        alert(data.success);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Houve um erro ao remover o item.');
                });
            });
        });

        function updateCartSummary() {
            fetch("{{ route('cart.summary') }}", {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json"
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Erro ao buscar o resumo do carrinho.');
            })
            .then(data => {
                const total = data.total;
                document.querySelector('.cart-summary p span').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
                document.querySelector('.cart-summary strong').textContent = `Total: R$ ${total.toFixed(2).replace('.', ',')}`;
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Houve um erro ao atualizar o resumo do carrinho.');
            });
        }

    </script>

</body>
</html>
