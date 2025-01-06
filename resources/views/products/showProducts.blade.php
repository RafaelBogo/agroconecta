<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset("images/background2.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin: 0;
        }

        .navbar {
            background-color: #787b7b;
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

        .search-bar {
            width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .products {
            margin-top: 30px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .product-card h5 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .product-card p {
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">AgroConecta</a>
            <div class="navbar-nav mx-auto">
                <a class="nav-link" href="{{ route('dashboard')}}">Início</a>
                <a class="nav-link" href="{{ route('products.show')}}">Produtos</a>
                <a class="nav-link" href="{{ route('sell.important') }}">Vender</a>
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

    <div class="container text-center">
        <div class="search-bar">
            <form action="{{ route('products.search') }}" method="GET" class="d-flex">
                <input type="text" name="product" placeholder="Busque por um Produto"
                    class="form-control me-2 flex-grow-2" value="{{ request('product') }}">
                <select name="city" class="form-select me-2 flex-grow-1" style="max-width: 150px;">
                    <option value="">Cidade</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-success">Buscar</button>
            </form>
        </div>
    </div>

    <div class="container text-center">
        <div class="products">
            @foreach($products as $product)
                <div class="product-card">
                    <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" style="max-width: 100%; height: auto; border-radius: 5px;">
                    <h5>{{ $product->name }}</h5>
                    <p>{{ $product->description }}</p>
                    <p><strong>Preço:</strong> R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                    <p><strong>Disponível em:</strong> {{ $product->city }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
