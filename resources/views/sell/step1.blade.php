<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Dicas para Vender</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset("images/background1.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding-top: 70px; /* Espaço para a navbar fixa */
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
            max-width: 1000px;
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
            font-size: 16px;
            color: #555;
        }

        .right-section {
            max-width: 60%;
            padding-left: 20px;
            max-height: 450px;
            overflow-y: auto; /* Adiciona a barra de rolagem */
        }

        .right-section ul {
            list-style: none;
            padding: 0;
            margin-right: 20px; /* Adiciona um espaço entre a lista e a barra de rolagem */
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

        .right-section ul li strong {
            color: #333;
        }

        .btn-success {
            width: 80%;
            margin-top: 300px;
            font-size: 16px;
            font-weight: bold;
        }

        /* Barra de rolagem personalizada */
        .right-section::-webkit-scrollbar {
            width: 35px; /* Reduz a largura para algo mais minimalista */
        }


        .right-section::-webkit-scrollbar-track {
            background: rgba(200, 200, 200, 0.2); /* Fundo sutil para a barra de rolagem */
            border-radius: 20px; /* Deixa o track arredondado */
            padding-left: 50px;

        }

        .right-section::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3); /* Cor verde para o thumb */
            border-radius: 20px; /* Deixa o thumb arredondado */
            padding-left: 50px;
        }

        .right-section::-webkit-scrollbar-thumb:hover {
            background-color: rgba(120, 123, 123, 0.9); /* Altera a cor do thumb ao passar o mouse */
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AgroConecta</a>
            <div class="navbar-nav mx-auto">
                <a class="nav-link" href="{{ route('dashboard')}} ">Início</a>
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
                <h2>IMPORTANTE!</h2>
                <p>Confira algumas <strong>dicas</strong> antes de cadastrar seu produto para venda:</p>
                <button onclick="window.location='{{ route('sell.step2') }}'" class="btn btn-success">Avançar</button>
            </div>
            <div class="right-section">
                <ul>
                    <li><strong>USE BOAS FOTOS:</strong> Fotos claras, de alta qualidade e com fundo neutro atraem mais compradores. Mostre detalhes do produto.</li>
                    <li><strong>DESCREVA BEM:</strong> Explique tipo, quantidade, origem e diferenciais do produto. Inclua informações como cultivo orgânico, se for o caso.</li>
                    <li><strong>DEFINA PREÇO JUSTO:</strong> Pesquise preços de mercado, considere seus custos e seja competitivo. Inclua valores promocionais, quando possível.</li>
                    <li><strong>INFORME LOCALIZAÇÃO:</strong> Seja claro sobre sua localização, use pontos de referência e outras informações que ajudarão o cliente encontrar seu ponto de venda.</li>
                    <li><strong>GARANTA QUALIDADE:</strong> Atualize seus estoques, informe validade e melhores condições de armazenamento ou consumo.</li>
                    <li><strong>SEJA HONESTO:</strong> Descreva o produto exatamente como ele é, sem omitir informações importantes para o comprador.</li>
                    <li><strong>RESPONDA RÁPIDO:</strong> Esteja disponível para responder dúvidas dos clientes de forma ágil.</li>
                    <li><strong>INVISTA NO EMBALAMENTO:</strong> Caso precise enviar produtos, utilize embalagens que protejam o conteúdo durante o transporte.</li>
                    <li><strong>ATUALIZE SEMPRE:</strong> Mantenha as informações sobre os produtos atualizadas, incluindo preços e disponibilidade.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
