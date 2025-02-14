<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Minhas Avaliações</title>
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

        .ratings-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin-top: 20px;
        }

        .ratings-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin-bottom: 50px;
        }

        .product-item {
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #fff;
        }

        .btn-dark {
            margin-top: 30px;
            color: white;
            width: 100%;
            font-size: 1.2rem;
            padding: 10px;
        }

        .ratings-box::-webkit-scrollbar {
        width: 35px;
    }

    .ratings-box::-webkit-scrollbar-track {
        background: rgba(245, 245, 245, 0.9);
        border-radius: 20px;
    }

    .ratings-box::-webkit-scrollbar-thumb {
        background-color: rgba(120, 120, 120, 0.6);
        border-radius: 20px;
    }

    .ratings-box::-webkit-scrollbar-thumb:hover {
        background-color: rgba(100, 100, 100, 0.9);
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

    <div class="container ratings-container">
    <div class="ratings-box" style="max-height: 500px; overflow-y: auto; padding-right: 10px;">
        <h3 class="mb-4 text-center">Avaliar Produtos Comprados</h3>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($products as $product)
            <div class="product-item mb-3 p-3 border rounded">
                <strong>{{ $product->name }}</strong><br>
                <small>{{ $product->description }}</small><br>

                @if(in_array($product->id, $reviews))
                    <span class="text-success">Você já avaliou este produto.</span>
                @else
                    <form action="{{ route('products.reviews.store', $product->id) }}" method="POST" class="mt-2">
                        @csrf
                        <div class="mb-2">
                            <label for="rating" class="form-label">Nota (1 a 5):</label>
                            <input type="number" name="rating" class="form-control" min="1" max="5" required>
                        </div>
                        <div class="mb-2">
                            <label for="comment" class="form-label">Comentário:</label>
                            <textarea name="comment" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Avaliação</button>
                    </form>
                @endif
            </div>
        @empty
            <div class="text-center">Você ainda não comprou nenhum produto.</div>
        @endforelse

        <a href="{{ route('dashboard') }}" class="btn btn-dark">Voltar</a>
    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
