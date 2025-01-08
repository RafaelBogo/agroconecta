<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Dados - AgroConecta</title>
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 15px;
            font-size: 16px;
            width: 100%;
        }

        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
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
<!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sucesso!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Seus dados foram atualizados com sucesso!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <body>
        @if (session('success'))
            <script>
                window.onload = function() {
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                };
            </script>
        @endif
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
                        <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sair
                        </a>
                    </form>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="content-box">
                <h2>Meus Dados</h2>
                <form action="{{ route('user.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Seu Nome Completo</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Seu Nome Completo" value="{{ $user->name }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="phone">Telefone</label>
                                <input type="text" id="phone" name="phone" class="form-control" placeholder="(XX) XXXXX-XXXX" value="{{ $user->phone }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address">Seu Endereço Completo</label>
                                <textarea id="address" name="address" class="form-control" rows="8" placeholder="Cidade, comunidade/bairro, rua, ponto de referência, cor da casa..." required>{{ $user->address }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="btn-container">
                        <a href="{{ route('minha.conta') }}" class="btn btn-secondary">Voltar</a>
                        <button type="submit" class="btn btn-success">Salvar</button>
                    </div>
                </form>
            </div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
