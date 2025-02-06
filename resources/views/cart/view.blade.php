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
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #787b7b;
            opacity: 0.9;
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

        .cart-container {
            width: 1000px;
            margin: 100px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-title {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .cart-content {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .cart-table {
            flex: 2;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-summary {
            flex: 1;
            background: rgba(245, 245, 245, 1);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .cart-summary h4 {
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        .cart-summary p {
            margin: 5px 0;
            font-size: 1rem;
        }

        .cart-summary .btn-finalize {
            margin-top: 20px;
            width: 100%;
        }

        .empty-cart {
            text-align: center;
            font-size: 1.2rem;
            color: #555;
            margin-top: 20px;
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
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
                </form>
            </div>
        </div>
    </nav>

    <div class="cart-container">
        <h1>Meu Carrinho</h1>

        @php $total = 0; @endphp

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
                        <tr data-item-id="{{ $id }}">
                            <td>
                                <img src="{{ asset('storage/' . $item['photo']) }}" alt="{{ $item['name'] }}"
                                    style="width: 50px; height: 50px; border-radius: 5px;">
                                {{ $item['name'] }}
                            </td>
                            <td>R$ {{ number_format($item['price'], 2, ',', '.') }}</td>
                            <td class="quantity-controls">
                                <div class="input-group" style="max-width: 120px;">
                                    <button class="btn btn-outline-secondary btn-sm btn-decrease" type="button">-</button>
                                    <input type="text" class="form-control text-center quantity-input" value="{{ $item['quantity'] }}" readonly>
                                    <button class="btn btn-outline-secondary btn-sm btn-increase" type="button">+</button>
                                </div>
                            </td>

                            <td class="subtotal">R$ {{ number_format($subtotal, 2, ',', '.') }}</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm delete-button"
                                    data-item-id="{{ $id }}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    Remover
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
                @endif
            </div>

            <div class="cart-summary">
                <h4>Resumo</h4>
                <p>Valor dos produtos: <span id="total-value">R$ {{ number_format($total, 2, ',', '.') }}</span></p>
                <p>Entrega: <span>Retirada com o produtor</span></p>
                <p>Desconto: <span id="discount-value">R$ 0,00</span></p>
                <hr>
                <p><strong>Total: <span id="grand-total">R$ {{ number_format($total, 2, ',', '.') }}</span></strong></p>
                <button class="btn btn-success btn-finalize" id="finalizarPedido">
                    <span id="finalizarText">Finalizar Pedido</span>
                    <span id="finalizarSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                </button>
                <a class="btn btn-dark btn-finalize" href="{{ route('products.show') }}">Continuar Comprando</a>
            </div>

        </div>
    </div>

    <!-- Modal de Confirmação de Remoção -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza de que deseja remover este item do carrinho?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger confirm-delete">Remover</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Finalização -->
    <div class="modal fade" id="finalizarModal" tabindex="-1" aria-labelledby="finalizarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="finalizarModalLabel">Pedido Finalizado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Pedido realizado com sucesso!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentItemId = null;

        // Configuração do botão de remoção
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                currentItemId = this.getAttribute('data-item-id');
            });
        });

        // Confirmação de remoção
        document.querySelector('.confirm-delete').addEventListener('click', function () {
            if (currentItemId) {
                fetch("{{ route('cart.delete') }}", {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ item_id: currentItemId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`button[data-item-id="${currentItemId}"]`).closest('tr').remove();
                        updateCartSummary();
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        modal.hide();
                    }
                })
                .catch(error => console.error('Erro ao remover o item:', error));
            }
        });

        // Finalizar Pedido
        document.getElementById('finalizarPedido').addEventListener('click', function () {
            const finalizarButton = this;
            const spinner = document.getElementById('finalizarSpinner');
            const finalizarText = document.getElementById('finalizarText');

            // Exibe o spinner e oculta o texto
            spinner.style.display = 'inline-block';
            finalizarText.style.display = 'none';
            finalizarButton.disabled = true;

            fetch("{{ route('cart.finalizar') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Exibe o modal de finalização
                    const modal = new bootstrap.Modal(document.getElementById('finalizarModal'));
                    modal.show();

                    // Remove os itens do carrinho do DOM
                    document.querySelectorAll('.cart-table tbody tr').forEach(row => row.remove());

                    // Atualiza o resumo do carrinho
                    updateCartSummary();

                    // Exibe mensagem de carrinho vazio
                    const cartTable = document.querySelector('.cart-table');
                    cartTable.innerHTML = '<p>O carrinho está vazio.</p>';
                } else {
                    alert('Houve um erro ao finalizar o pedido.');
                }
            })
            .catch(error => {
                console.error('Erro ao finalizar o pedido:', error);
                alert('Houve um erro ao finalizar o pedido.');
            })
            .finally(() => {
                // Oculta o spinner e restaura o botão
                spinner.style.display = 'none';
                finalizarText.style.display = 'inline-block';
                finalizarButton.disabled = false;
            });
        });


        function updateCartSummary() {
            // Atualizar o resumo do carrinho
        }

        document.querySelectorAll('.btn-increase, .btn-decrease').forEach(button => {
            button.addEventListener('click', function () {
                const row = this.closest('tr');
                const itemId = row.getAttribute('data-item-id');
                const quantityInput = row.querySelector('.quantity-input');
                let quantity = parseInt(quantityInput.value);
                const isIncrease = this.classList.contains('btn-increase');

                if (isIncrease) {
                    quantity += 1;
                } else if (quantity > 1) {
                    quantity -= 1;
                }

                quantityInput.value = quantity;

                // Atualizar subtotal e total
                updateSubtotal(row, quantity);

                // Enviar atualização para o backend
                fetch(`{{ url('cart/update') }}/${itemId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ item_id: itemId, quantity: quantity })
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert('Erro ao atualizar o carrinho. Tente novamente.');
                    }
                })
                .catch(error => {
                    console.error('Erro ao atualizar a quantidade:', error);
                    alert('Erro ao atualizar o carrinho. Verifique sua conexão.');
                });
            });
        });

        function updateSubtotal(row, quantity) {
            const price = parseFloat(row.querySelector('td:nth-child(2)').innerText.replace('R$ ', '').replace(',', '.'));
            const subtotalElement = row.querySelector('.subtotal');
            const subtotal = (price * quantity).toFixed(2).replace('.', ',');
            subtotalElement.innerText = `R$ ${subtotal}`;

            updateCartTotal();
        }

        function updateCartTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(subtotalElement => {
                total += parseFloat(subtotalElement.innerText.replace('R$ ', '').replace(',', '.'));
            });

            document.getElementById('total-value').innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
            document.getElementById('grand-total').innerText = `R$ ${total.toFixed(2).replace('.', ',')}`;
        }


    </script>
</body>

</html>
