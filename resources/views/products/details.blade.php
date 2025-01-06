<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} - AgroConecta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset("images/background2.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding-top: 70px; /* Espaço para evitar sobreposição com a navbar */
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

        .product-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 900px;
            width: 100%;
            margin: 20px auto; /* Centraliza o container e adiciona espaçamento */
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .product-image {
            flex: 1 1 45%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .product-info {
            flex: 1 1 50%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-info h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .product-info h2 {
            font-size: 2rem;
            color: #28a745;
            margin-bottom: 20px;
        }

        .product-info label {
            font-size: 1rem;
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        .product-info input {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .product-info button {
            width: 100%;
            margin-bottom: 15px;
        }

        .product-info .description {
            margin-top: 20px;
        }

        .product-info .description h4 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .product-info .description p {
            font-size: 1rem;
            color: #555;
            line-height: 1.5;
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
                <a class="nav-link" href="{{ route('products.show') }}">Produtos</a>
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

    <div class="product-container">
        <!-- Imagem do Produto -->
        <div class="product-image">
            <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}">
        </div>

        <!-- Informações do Produto -->
        <div class="product-info">
            <div>
                <h1>{{ $product->name }}</h1>
                <h2>R$ {{ number_format($product->price, 2, ',', '.') }}</h2>
                <form id="add-to-cart-form">
                    <label for="quantity">Quantidade</label>
                    <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Insira aqui a quantidade" value="1" min="1">
                    <button type="submit" class="btn btn-success mt-3">Adicionar ao Carrinho</button>
                </form>
                <script>
                    document.getElementById('add-to-cart-form').addEventListener('submit', function (e) {
                        e.preventDefault();

                        const quantity = document.getElementById('quantity').value;

                        fetch("{{ route('cart.add') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                product_id: {{ $product->id }},
                                quantity: quantity
                            })
                        })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            }
                            throw new Error('Erro ao adicionar ao carrinho.');
                        })
                        .then(data => {
                            alert(data.message || 'Produto adicionado ao carrinho com sucesso!');
                        })
                        .catch(error => {
                            console.error('Erro:', error);
                            alert('Houve um erro ao adicionar o produto ao carrinho.');
                        });
                    });
                </script>
            </div>

            <div class="description mt-4">
                <h4>Descrição</h4>
                <p>{{ $product->description }}</p>
            </div>
        </div>

    </div>
</body>
</html>
