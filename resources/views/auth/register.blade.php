<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('{{ asset("images/background4.png") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-box {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .register-box h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .btn-dark {
            background-color: #343a40;
            color: white !important;
        }

        .btn-dark:hover {
            background-color: #23272b;
        }

        .extra-options a {
            color: #222;
            text-decoration: none;
        }

        .extra-options a:hover {
            text-decoration: underline;
        }

        .alert {
            text-align: left;
            font-size: 14px;
        }

        .invalid-feedback {
            display: block;
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
            transform: translateY(2%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #6c757d;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover {
            color: #343a40;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="register-box">
            <h1>AgroConecta</h1>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3 text-start">
                    <label for="name" class="form-label">Nome Completo</label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Digite seu nome completo" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 text-start">
                    <label for="email" class="form-label">Endereço de Email</label>
                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Digite seu endereço de email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 text-start">
                    <label for="phone" class="form-label">Telefone</label>
                    <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="(00) 00000-0000" value="{{ old('phone') }}">
                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                <div class="mb-3 text-start">
                    <label for="address" class="form-label">Endereço Completo</label>
                    <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Cidade, bairro/comunidade, rua, ponto de referência..." required >{{ old('address') }}</textarea>
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>


                <!-- Campo de senha -->
                <div class="mb-3 text-start password-container">
                    <label for="password" class="form-label">Senha</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Digite sua senha" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password', 'password-icon')">
                        <i id="password-icon" class="bi bi-eye"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Campo de confirmação de senha -->
                <div class="mb-3 text-start password-container">
                    <label for="password_confirmation" class="form-label">Confirme sua Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Digite sua senha novamente" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'password-icon-confirm')">
                        <i id="password-icon-confirm" class="bi bi-eye"></i>
                    </button>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-dark btn-lg w-100">Criar Conta</button>
            </form>
            <div class="extra-options mt-3">
                <a href="{{ route('login') }}">Já tem uma conta?</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordField = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);

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
