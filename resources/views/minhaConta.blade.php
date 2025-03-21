<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Minha Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            opacity: 0.8;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            text-decoration: underline;
            color: #ccc;
        }

        .account-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .account-box {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin-bottom: 50px;
        }

        .options-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .option-card {
            background-color: rgba(255, 255, 255, 0.9);
            transition: transform 0.2s ease-in-out;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1rem;
        }

        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.2);
        }

        .option-card i {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .option-card h5 {
            font-weight: bold;
            color: #4CAF50;
            margin-top: 10px;
        }

        .btn-dark {
            margin-top: 30px;
            color: white;
            width: 100%;
            font-size: 1.2rem;
            padding: 10px;
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
                <a class="nav-link px-3" href="#">Minha Conta</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <div class="container account-container">
        <div class="account-box">
            <div class="options-grid">
                <div class="option-card">
                    <a href="{{ route('orders.index') }}" style="text-decoration: none; color: inherit;">
                        <i class="bi bi-cart"></i>
                        <h5>Meus Pedidos</h5>
                        <p>Verifique os produtos que você comprou.</p>
                    </a>
                </div>
                <div class="option-card">
                    <a href="{{ route('user.data') }}" style="text-decoration: none; color: inherit;">
                        <i class="bi bi-person"></i>
                        <h5>Meus Dados</h5>
                        <p>Verifique e edite seus dados pessoais.</p>
                    </a>
                </div>
                <div class="option-card">
                    <a href="{{ route('account.myProducts') }}"style="text-decoration: none; color: inherit;">
                        <i class="bi bi-box"></i>
                        <h5>Meus Produtos</h5>
                        <p>Gerencie e edite os produtos que você vende.</p>
                    </a>
                </div>
                <div class="option-card">
                    <a href="{{ route('account.myRatings') }}" style="text-decoration: none; color: inherit;">
                        <i class="bi bi-star"></i>
                        <h5>Avaliações</h5>
                        <p>Avalie produtos que você comprou.</p>
                    </a>
                </div>
                <div class="option-card">
                    <a href="{{ route('seller.mySales') }}" style="text-decoration: none; color: inherit;">
                        <i class="bi bi-bag"></i>
                        <h5>Minhas Vendas</h5>
                        <p>Gerencie as vendas e confirme as retiradas.</p>
                    </a>
                </div>
                <div class="option-card">
                    <a href="{{ route('support') }}" style="text-decoration: none; color: inherit;">
                        <i class="bi bi-headset"></i>
                        <h5>Suporte</h5>
                        <p>Entre em contato com nosso suporte para obter ajuda.</p>
                    </a>
                </div>


            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-dark">Voltar</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
