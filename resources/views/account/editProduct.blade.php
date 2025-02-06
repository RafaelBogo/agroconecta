<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Product - AgroConecta</title>
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
            flex-direction: row;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 1000px;
            padding: 30px;
            margin: 50px auto;
        }

        .left-section {
            flex: 1;
            padding-right: 20px;
        }

        .right-section {
            flex: 2;
            display: flex;
            flex-direction: column;
        }

        .form-control {
            border-radius: 10px;
            margin-bottom: 15px;
            padding: 15px;
        }

        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .product-image {
            width: 100%;
            max-width: 250px;
            height: auto;
            border-radius: 10px;
            margin: 0 auto;
            position: relative;
        }

        .edit-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
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
            <div class="left-section">
                <h4>Editar Produto</h4>
                <p>Aqui você altera informações sobre o seu produto</p>
                <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="product-image">
            </div>
            <div class="right-section">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="text" name="name" class="form-control" placeholder="Nome do Produto" value="{{ $product->name }}" required>
                <textarea name="description" class="form-control" placeholder="Descrição do Produto" rows="4" required>{{ $product->description }}</textarea>
                <input type="number" name="price" class="form-control" placeholder="Valor em Reais" value="{{ $product->price }}" step="0.01" required>
                <input type="text" name="city" class="form-control" placeholder="Cidade" value="{{ $product->city }}">
                <input type="number" name="stock" class="form-control" placeholder="Estoque Disponível" value="{{ $product->stock }}" required>
                <input type="date" name="validity" class="form-control" placeholder="Validade do Produto" value="{{ $product->validity }}">
                <input type="text" name="unit" class="form-control" placeholder="Unidade de Medida (Ex: Unidade, Kg)" value="{{ $product->unit }}">
                <input type="text" name="contact" class="form-control" placeholder="Telefone para Contato" value="{{ $product->contact }}">
                <textarea name="address" class="form-control" placeholder="Endereço Completo" rows="4">{{ $product->address }}</textarea>

                <div class="btn-container">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <a href="{{ route('account.myProducts') }}" class="btn btn-secondary">Voltar</a>
                </div>
            </form>

            </div>
        </div>
    </div>

    <!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sucesso!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    O produto foi atualizado com sucesso!
                </div>
                <div class="modal-footer">
                    <a href="{{ route('account.myProducts') }}" class="btn btn-success">OK</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if (session('success'))
            window.onload = function() {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            };
        @endif
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
