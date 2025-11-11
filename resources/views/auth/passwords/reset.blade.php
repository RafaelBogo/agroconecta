<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/auth.passwords.reset.css') }}">

</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h4 class="card-title text-center mb-4">Redefinir Senha</h4>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Nova Senha --}}
                <div class="mb-3 password-container">
                    <label for="password" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Digite sua nova senha" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password', 'password-icon')">
                        <i id="password-icon" class="bi bi-eye"></i>
                    </button>
                </div>

                {{-- Confirmação de Senha --}}
                <div class="mb-3 password-container">
                    <label for="password_confirmation" class="form-label">Confirme a Nova Senha</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                        placeholder="Confirme sua nova senha" required>
                    <button type="button" class="toggle-password"
                        onclick="togglePassword('password_confirmation', 'password-icon-confirm')">
                        <i id="password-icon-confirm" class="bi bi-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-success w-100">Redefinir Senha</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.password.reset.js') }}" defer></script>

</body>

</html>
