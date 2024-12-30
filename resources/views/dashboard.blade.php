<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset("images/background.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.7);
        }

        .navbar a {
            color: white !important;
        }

        .search-box {
            margin-top: 5%;
            text-align: center;
            color: white;
        }

        .search-box h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .search-bar select, .search-bar input {
            max-width: 300px;
            padding: 10px;
            font-size: 16px;
        }

        .search-bar button {
            padding: 10px 20px;
            font-size: 16px;
        }

        .products-list {
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">AgroConecta</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Produtos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Vender</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Carrinho</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a></li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Search Box -->
    <div class="search-box">
        <h1>Busque por um Produto</h1>
        <form action="{{ route('dashboard.search') }}" method="GET" class="search-bar">
            <select name="city" class="form-select">
                <option value="" disabled selected>Selecione uma cidade</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                @endforeach
            </select>
            <input type="text" name="product" class="form-control" placeholder="Digite o nome do produto">
            <button type="submit" class="btn btn-success">Buscar</button>
        </form>
    </div>

    <!-- Products List -->
    <div class="container products-list">
        <h2>Produtos Disponíveis</h2>
        @if($products->isEmpty())
            <p>Nenhum produto encontrado.</p>
        @else
            <ul class="list-group">
                @foreach($products as $product)
                    <li class="list-group-item">
                        <strong>{{ $product->name }}</strong> - {{ $product->city }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
