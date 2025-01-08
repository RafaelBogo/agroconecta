<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Products - AgroConecta</title>
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

        .content-box {
            display: flex;
            flex-direction: column;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            padding: 30px;
            margin: 50px auto;
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .product-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        .product-info img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 15px;
        }

        .btn-container {
            display: flex;
            gap: 10px;
        }

        .btn-edit {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .btn-delete:hover {
            background-color: #a71d2a;
        }

        .btn-secondary {
            padding: 10px 30px;
            background-color: black;
            border: none;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">AgroConecta</a>
            <div class="navbar-nav mx-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">Início</a>
                <a class="nav-link" href="{{ route('products.show')}}">Produtos</a>
                <a class="nav-link" href="{{ route('sell.important') }}">Vender</a>
                <a class="nav-link" href="{{ route('cart.view') }}">Carrinho</a>
            </div>
            <div class="d-flex align-items-center">
                <a class="nav-link px-3" href="{{ route('minha.conta') }}">Minha Conta</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="#" class="nav-link text-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="content-box">
            <h2>Meus Produtos</h2>
            @if ($products->isEmpty())
                <p class="text-center text-muted">Você ainda não tem produtos registrados.</p>
            @else
                @foreach ($products as $product)
                    <div class="product-item">
                        <div class="product-info">
                            <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}">
                            <div>
                                <h5>{{ $product->name }}</h5>
                                <p>${{ number_format($product->price, 2) }} per unit</p>
                            </div>
                        </div>
                        <div class="btn-container">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-edit">Editar</a>
                            <button type="button" class="btn-delete delete-button" data-id="{{ $product->id }}">Deletar</button>
                        </div>
                    </div>
                @endforeach
            @endif
            <div class="text-center mt-4">
                <a href="{{ route('minha.conta') }}" class="btn btn-secondary">Voltar</a>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Deletar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tem certeza que deseja deletar este produto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger confirm-delete">Deletar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Variável global para armazenar o ID do produto atual
        let currentProductId = null;

        // Adiciona eventos de clique em todos os botões de exclusão
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                // Obtém o ID do produto a partir do botão clicado
                currentProductId = this.getAttribute('data-id');

                // Exibe o modal de confirmação de exclusão
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });
        });

        // Evento de clique no botão "Delete" dentro do modal
        document.querySelector('.confirm-delete').addEventListener('click', function () {
            if (currentProductId) {
                // Obtém o token CSRF da meta tag no HTML
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Envia a requisição DELETE para o servidor
                fetch(`/products/${currentProductId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Error in response');
                })
                .then(data => {
                    if (data.success) {
                        // Remove o produto do DOM
                        document.querySelector(`.delete-button[data-id="${currentProductId}"]`).closest('.product-item').remove();

                        // Fecha o modal de confirmação
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                        modal.hide();
                    } else {
                        alert(data.message || 'Error deleting the product.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the product.');
                });
            }
        });
    </script>
</body>
</html>
