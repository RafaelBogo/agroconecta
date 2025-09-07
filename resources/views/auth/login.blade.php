<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
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

    .login-container {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 0;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        max-width: 900px;
        width: 100%;
        display: flex;
        overflow: hidden;
    }

    .login-left {
        background-color: #f8f9fa;
        padding: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40%;
    }

    .login-left img {
        max-width: 100%;
        height: auto;
    }

    .login-right {
        padding: 40px;
        width: 60%;
    }

    .login-right h1 {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }

    .btn-dark {
        background-color: #343a40;
    }

    .btn-dark:hover {
        background-color: #23272b;
    }

    .password-container {
        position: relative;
    }

    .password-container input {
        padding-right: 40px;
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
        color: #6c757d;
        display: flex;
        align-items: center;
        height: 100%;
    }

    .toggle-password:hover {
        color: #343a40;
    }

    .extra-options a {
        color: black !important;
        text-decoration: underline !important;
    }

    @media (max-width: 768px) {
        .login-left {
            display: none;
        }

        .login-right {
            width: 100%;
        }

        .login-container {
            max-width: 400px;
        }
    }
</style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="login-container">
            <div class="login-left">
                <img src="{{ asset('images/logo.png') }}" alt="AgroConecta Logo">
            </div>
            <div class="login-right">
                <h1>Login</h1>
                @if(session('message'))
                    <div class="alert alert-success text-start">
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

                <div class="extra-options mt-3 text-center">
                    <a href="{{ route('password.request') }}" class="d-block mt-3">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
