<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body {
            background-image: url('<?php echo e(asset("images/background1.jpg")); ?>');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
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
            transform: translateY(10%);
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
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <h4 class="card-title text-center mb-4">Redefinir Senha</h4>
            <form action="<?php echo e(route('password.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="token" value="<?php echo e($token); ?>">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail" required>
                </div>

                <!-- Campo de Nova Senha -->
                <div class="mb-3 password-container">
                    <label for="password" class="form-label">Nova Senha</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua nova senha" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password', 'password-icon')">
                        <i id="password-icon" class="bi bi-eye"></i>
                    </button>
                </div>

                <!-- Campo de Confirmação de Senha -->
                <div class="mb-3 password-container">
                    <label for="password_confirmation" class="form-label">Confirme a Nova Senha</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme sua nova senha" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'password-icon-confirm')">
                        <i id="password-icon-confirm" class="bi bi-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn btn-success w-100">Redefinir Senha</button>
            </form>
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
<?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views\auth\passwords\reset.blade.php ENDPATH**/ ?>