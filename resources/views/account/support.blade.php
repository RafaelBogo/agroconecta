<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte - AgroConecta</title>
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

        .support-section {
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 70px);
            text-align: center;
        }

        .support-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
        }

        .email-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .email-text {
            font-size: 1.2rem;
            color: #333;
        }

        .copy-btn {
            border: none;
            background-color: #28a745;
            color: white;
            padding: 6px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .copy-btn:hover {
            background-color: #218838;
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

    <div class="support-section">
        <div class="support-card">
            <h3 class="mb-4">Suporte ao Cliente</h3>
            <p class="mb-3">Em caso de dúvidas, entre em contato conosco:</p>
            <div class="email-container">
                <span class="email-text" id="supportEmail">suporte.agroconecta@gmail.com</span>
                <button class="copy-btn" onclick="copyEmail()">Copiar</button>
            </div>
            <div class="mt-3">
                <a href="mailto:suporte.agroconecta@gmail.com" class="btn btn-outline-success mt-3">
                    <i class="bi bi-envelope"></i> Enviar E-mail
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyEmail() {
            const emailText = document.getElementById('supportEmail').innerText;
            navigator.clipboard.writeText(emailText).then(() => {
                const button = document.querySelector('.copy-btn');
                button.innerText = 'Copiado!';
                setTimeout(() => button.innerText = 'Copiar', 2000);
            }).catch(err => {
                alert('Falha ao copiar o e-mail.');
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
