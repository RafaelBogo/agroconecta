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

        .extra-options a {
            color: #222;
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

        /* Estilização do campo de senha com botão de exibição */
        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 40px; /* Espaço para o botão de exibir senha */
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-24%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #6c757d; /* Cor neutra */
            display: flex;
            align-items: center;
            height: 100%;
        }
        .toggle-password:hover {
            color: #343a40; /* Cor mais escura */
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

                <div class="mb-3 text-start password-container">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Digite sua senha" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i id="password-icon" class="bi bi-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100">Iniciar Sessão</button>
                <a href="{{ route('register') }}" class="btn btn-dark btn-lg w-100 border-0 mt-2">Registre-se</a>
            </form>
            <div class="extra-options mt-3">
                <a href="{{ route('password.request') }}" class="d-block mt-3">Esqueceu sua senha?</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <script>
        function togglePassword() {
            const passwordField = document.getElementById("password");
            const passwordIcon = document.getElementById("password-icon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                passwordIcon.classList.remove("bi-eye");
                passwordIcon.classList.add("bi-eye-slash");
            } else {
                passwordField.type = "password";
                passwordIcon.classList.remove("bi-eye-slash");
                passwordIcon.classList.add("bi-eye");
            }
        }
    </script>
</body>
</html>
