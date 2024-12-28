<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h4 class="card-title text-center mb-4">Redefinir Senha</h4>
            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua nova senha" required>
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirme a Nova Senha</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme sua nova senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Redefinir Senha</button>
            </form>
        </div>
    </div>
</body>
</html>
