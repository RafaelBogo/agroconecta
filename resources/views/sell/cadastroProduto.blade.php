<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
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
            flex-direction: column;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            padding: 30px;
            margin: 50px auto;
            max-height: 80vh; /* Define a altura máxima */
            overflow-y: auto; /* Adiciona a barra de rolagem */
            padding-right: 20px;
        }

        /* Personalização da barra de rolagem */
        .content-box::-webkit-scrollbar {
            width: 35px;
        }

        .content-box::-webkit-scrollbar-track {
            background: rgba(245, 245, 245, 0.9);
            border-radius: 20px;
        }

        .content-box::-webkit-scrollbar-thumb {
            background-color: rgba(120, 120, 120, 0.6);
            border-radius: 20px;
        }

        .content-box::-webkit-scrollbar-thumb:hover {
            background-color: rgba(100, 100, 100, 0.9);
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

        .btn-success {
            width: 100%;
            margin-top: 10px;
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
                <a class="nav-link" href="{{ route(name: 'sell.important') }}">Vender</a>
                <a class="nav-link" href="{{ route('cart.view') }}">Carrinho</a>
            </div>
            <div class="d-flex align-items-center">
                <a class="nav-link px-3" href="{{ route('minha.conta') }}">Minha Conta</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="#" class="nav-link text-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="content-box">
            <h2 class="text-center">Cadastro de Produto</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Nome do Produto</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Insira o nome do produto" required>
                </div>

                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea id="description" name="description" class="form-control" placeholder="Insira a descrição do produto" required></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Preço</label>
                    <input type="number" id="price" name="price" class="form-control" placeholder="Insira o preço" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="city">Cidade</label>
                    <input type="text" id="city" name="city" class="form-control" placeholder="Insira a cidade" required>
                </div>

                <div class="form-group">
                    <label for="saved_addresses">Endereços Salvos</label>
                    <select id="saved_addresses" class="form-control" onchange="populateAddressField()">
                        <option value="">Selecione um endereço salvo (ou digite um novo)</option>
                        @foreach (auth()->user()->addresses as $address)
                            <option value="{{ $address }}">{{ $address }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="address">Endereço</label>
                    <input list="saved_addresses" id="address" name="address" class="form-control" placeholder="Insira ou selecione um endereço" value="{{ old('address') }}" required>

                    <datalist id="saved_addresses">
                        @if (!empty(auth()->user()->addresses))
                            @foreach (auth()->user()->addresses as $address)
                                <option value="{{ $address }}">{{ $address }}</option>
                            @endforeach
                        @endif
                    </datalist>
                </div>


                <div class="form-group">
                    <label for="stock">Estoque</label>
                    <input type="number" id="stock" name="stock" class="form-control" placeholder="Insira a quantidade em estoque" required>
                </div>

                <div class="form-group">
                    <label for="unit">Unidade de Medida</label>
                    <input type="text" id="unit" name="unit" class="form-control" placeholder="Exemplo: kg ou unidade" required>
                </div>

                <div class="form-group">
                    <label for="validity">Validade</label>
                    <input type="date" id="validity" name="validity" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="contact">Telefone para Contato</label>
                    <input type="text" id="contact" name="contact" class="form-control" placeholder="Insira o telefone para contato" required>
                </div>

                <div class="form-group">
                    <label for="photo">Foto do Produto</label>
                    <input type="file" id="photo" name="photo" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success">Cadastrar Produto</button>
            </form>

        </div>
    </div>
    <script>
        function populateAddressField() {
            const savedAddressesDropdown = document.getElementById("saved_addresses");
            const addressField = document.getElementById("address");

            // Define o valor do campo de endereço com o valor selecionado no dropdown
            addressField.value = savedAddressesDropdown.value;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
