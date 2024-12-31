<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Etapa 1</title>
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
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .left-section h2 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .left-section p {
            margin-top: 10px;
            margin-bottom: 225px;
            font-size: 16px;
            color: #555;
        }

        .right-section {
            flex: 1;
            padding-left: 20px;
            padding-right: 20px; /* Adicionado espaço interno à direita */
            max-height: 450px;
            overflow-y: auto;
        }

        .right-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .right-section ul li {
            margin-bottom: 15px;
            font-size: 16px;
            color: #555;
            background-color: rgba(240, 240, 240, 0.9);
            padding: 10px;
            border-radius: 10px;
            box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.1);
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

        .btn-success, .btn-secondary {
            width: 100%;
            margin-top: 10px;
        }

        /* Barra de rolagem personalizada */
        .right-section::-webkit-scrollbar {
            width: 35px;
        }

        .right-section::-webkit-scrollbar-track {
            background: rgba(200, 200, 200, 0.2);
            border-radius: 20px;
        }

        .right-section::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 20px;
        }

        .right-section::-webkit-scrollbar-thumb:hover {
            background-color: rgba(120, 123, 123, 0.9);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AgroConecta</a>
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
                    <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="content-box">
            <div class="left-section">
                <h2>Etapa 1</h2>
                <p>Após preencher os campos <strong>verifique</strong> suas informações antes de prosseguir para garantir que estejam todas corretas.</p>
                <a href="{{ route('sell.important') }}" class="btn btn-secondary">Voltar</a>
                <a href="{{ route('sell.step2') }}" class="btn btn-success">Avançar</a>
            </div>
            <div class="right-section">
                <form action="{{ route('sell.step1.save') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nome do Produto</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Insira o nome aqui" required>
                    </div>
                    <div class="form-group">
                        <label for="price">Valor em Reais</label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="Insira o valor aqui" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantidade Disponível</label>
                        <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Insira a quantidade aqui" required>
                    </div>
                    <div class="form-group">
                        <label for="validity">Validade do Produto</label>
                        <input type="date" id="validity" name="validity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Unidade de Medida (Quilograma ou Unidade)</label>
                        <input type="text" id="unit" name="unit" class="form-control" placeholder="Insira a unidade de medida aqui" required>
                    </div>
                    <div class="form-group">
                        <label for="contact">Telefone para Contato</label>
                        <input type="text" id="contact" name="contact" class="form-control" placeholder="Insira seu telefone aqui" required>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
