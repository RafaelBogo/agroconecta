<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroConecta - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/auth.login.css')); ?>">

</head>

<body>
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="login-container">
            <div class="login-left">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="AgroConecta Logo">
            </div>
            <div class="login-right">
                <h1>Login</h1>
                <?php if(session('message')): ?>
                    <div class="alert alert-success text-start"><?php echo e(session('message')); ?></div>
                <?php endif; ?>

                <?php if(session('login_error')): ?>
                    <div class="alert alert-danger text-start"><?php echo e(session('login_error')); ?></div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger text-start"><?php echo e($errors->first()); ?></div>
                <?php endif; ?>


                <form action="<?php echo e(route('login')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3 text-start">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Digite seu email"
                            required>
                    </div>

                    <div class="mb-3 text-start password-container">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Digite sua senha" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i id="password-icon" class="bi bi-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">Iniciar Sessão</button>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-dark btn-lg w-100 border-0 mt-2">Registre-se</a>
                </form>

                <div class="extra-options mt-3 text-center">
                    <a href="<?php echo e(route('password.request')); ?>" class="d-block mt-3">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('js/auth.login.js')); ?>" defer></script>
</body>

</html>
<?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/auth/login.blade.php ENDPATH**/ ?>