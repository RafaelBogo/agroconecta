<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Etapa 2</title>
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

        .content-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            padding: 30px;
            margin: 50px auto;
        }

        .left-section {
            max-width: 35%;
            padding-right: 20px;
            border-right: 1px solid #ccc;
            text-align: center;
        }

        .left-section h2 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .left-section p {
            margin-top: 10px;
            margin-bottom: 200px;
            font-size: 16px;
            color: #555;
        }

        .right-section {
            flex: 1;
            padding-left: 30px;
        }


        .form-card {
            background-color: rgba(240, 240, 240, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }

        .btn-success, .btn-secondary {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">AgroConecta</a>
            <div class="navbar-nav mx-auto">
                <a class="nav-link" href="{{ route('dashboard') }}">Início</a>
                <a class="nav-link" href="#">Produtos</a>
                <a class="nav-link" href="{{ route('sell.step1') }}">Vender</a>
                <a class="nav-link" href="#">Carrinho</a>
            </div>
            <div class="d-flex align-items-center">
                <a class="nav-link px-3" href="{{ route('minha.conta') }}">Minha Conta</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <!-- Conteúdo -->
    <div class="container">
        <div class="content-box">
            <div class="left-section">
                <h2>Etapa 2</h2>
                <p>Escreva uma descrição atrativa do produto e seu endereço completo para retirada.</p>
                <a href="{{ route('sell.step1') }}" class="btn btn-secondary">Voltar</a>
                <a href="{{ route('sell.step3') }}" class="btn btn-success">Avançar</a>
            </div>
            <div class="right-section">
                <div class="form-card">
                    <form action="{{ route('sell.step2.save') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="description">Descrição do Produto</label>
                            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Exemplo: Tipo, variedade, condições de cultivo (orgânico ou tradicional), diferenciais..." maxlength="250" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="pickup_address">Seu Endereço de Retirada</label>
                            <textarea id="pickup_address" name="pickup_address" class="form-control" rows="4" placeholder="Exemplo: Rua, Bairro, Cidade, ponto de referência..." maxlength="250" required>{{ old('pickup_address') }}</textarea>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" id="save_address" name="save_address" class="form-check-input" {{ old('save_address') ? 'checked' : '' }}>
                            <label for="save_address" class="form-check-label">Salvar Endereço</label>
                        </div>
                    </form>
                </div>
            </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
