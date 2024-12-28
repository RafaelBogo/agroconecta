<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('{{ asset("images/background1.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .login-box h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .btn-green {
            background-color: #28a745;
            color: white;
        }

        .btn-green:hover {
            background-color: #218838;
            color: white;
        }

        .extra-options a {
            color: #222;
        }

        .extra-options a {
            text-decoration: underline;
        }

        .btn-dark {
            background-color: #343a40; /* Cinza escuro */
        }

        .btn-dark:hover {
            background-color: #23272b; /* Cinza mais escuro */
        }

        .alert-success {
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="login-box">
            <h1>AgroConecta</h1>

            <!-- Mensagem de sucesso -->
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Digite seu email" required>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                </div>
                <button type="submit" class="btn btn-green btn-lg w-100">Iniciar Sessão</button>
                <a href="{{ route('register') }}" class="btn btn-dark btn-lg w-100 border-0 mt-2">Registre-se</a>
            </form>
            <div class="extra-options mt-3">
                <a href="{{ route('password.request') }}" class="d-block mt-3">Esqueceu sua senha?</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
