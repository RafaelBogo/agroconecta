<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AgroConecta - Registro</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>

  <style>
    :root{ --brand:#2e7d32; --ink:#1f2937; }

    body{
      background-image: url('<?php echo e(asset("images/background4.png")); ?>');
      background-size: cover;
      background-position: center;
      min-height: 100vh; margin:0;
      display:flex; align-items:center; justify-content:center;
    }

    .bg-overlay{ position:fixed; inset:0;
      background: radial-gradient(80% 60% at 50% 20%, rgba(0,0,0,.20), transparent 70%),
                  linear-gradient(to bottom, rgba(0,0,0,.15), rgba(0,0,0,.25));
      pointer-events:none;
    }

    .card-glass{
      background: rgba(255,255,255,.95);
      backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
      border:1px solid rgba(255,255,255,.6);
      border-radius:16px;
      box-shadow: 0 20px 45px rgba(0,0,0,.25);
      width:100%; max-width: 680px;
    }

    .title{ font-weight:800; letter-spacing:.3px; color:var(--ink); }
    .form-label{ font-weight:600; color:#374151; }
    .input-group-text{ background:#f4f6f8; border-right:0; }
    .input-group .form-control{ border-left:0; }
    .form-control:focus{
      border-color: var(--brand);
      box-shadow: 0 0 0 .2rem rgba(46,125,50,.15);
    }

    .cta-wrap{ display:flex; justify-content:center; }
    .btn-cta{ min-width: 585px; }

    .login-link{ color:#000 !important; }
    .login-link:hover{ text-decoration: underline; }

    .grid{ row-gap:1.25rem; }
  </style>
</head>
<body>
  <div class="bg-overlay"></div>

  <div class="card-glass p-4 p-md-5 mx-3">
    <h1 class="title h3 text-center mb-4">Criar sua conta</h1>

    <?php if($errors->any()): ?>
      <div class="alert alert-danger py-2">
        <ul class="mb-0">
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php
      $cities = [
        'Chapecó','Xanxerê','Xaxim','Pinhalzinho','Palmitos','Maravilha','Modelo','Saudades','Águas de Chapecó',
        'Nova Erechim','Nova Itaberaba','Coronel Freitas','Quilombo','Abelardo Luz','Coronel Martins','Galvão',
        'São Lourenço do Oeste','Campo Erê','Saltinho','São Domingos','Ipuaçu','Entre Rios','Jupiá',
        'Itapiranga','Iporã do Oeste','Mondaí','Riqueza','Descanso','Tunápolis','Belmonte','Paraíso',
        'São Miguel do Oeste','Guaraciaba','Anchieta','Dionísio Cerqueira','Barra Bonita'
      ];
    ?>

    <form action="<?php echo e(route('register')); ?>" method="POST">
      <?php echo csrf_field(); ?>

      <div class="row grid">
        <div class="col-md-6">
          <label for="name" class="form-label">Nome Completo</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" id="name" name="name"
                   class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Seu nome completo" value="<?php echo e(old('name')); ?>" required>
          </div>
          <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" id="email" name="email"
                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="voce@exemplo.com" value="<?php echo e(old('email')); ?>" required>
          </div>
          <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-6">
          <label for="phone" class="form-label">Telefone</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" id="phone" name="phone"
                   class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="(00) 00000-0000" value="<?php echo e(old('phone')); ?>">
          </div>
          <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-6">
          <label for="address" class="form-label">Endereço Completo</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
            <input id="address" name="address"
                   class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Cidade, bairro, rua, referência..." value="<?php echo e(old('address')); ?>" required>
          </div>
          <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- NOVO: Cidade (Oeste Catarinense) -->
        <div class="col-md-6">
          <label for="city" class="form-label">Cidade (Oeste Catarinense)</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-buildings"></i></span>
            <select id="city" name="city"
                    class="form-select <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
              <option value="" disabled <?php echo e(old('city') ? '' : 'selected'); ?>>Selecione sua cidade</option>
              <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($c); ?>" <?php echo e(old('city') === $c ? 'selected' : ''); ?>><?php echo e($c); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-6">
          <label for="password" class="form-label">Senha</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" id="password" name="password"
                   class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Mínimo 8 caracteres" required>
            <button class="btn btn-outline-secondary" type="button"
                    onclick="togglePassword('password','password-icon')">
              <i id="password-icon" class="bi bi-eye"></i>
            </button>
          </div>
          <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-6">
          <label for="password_confirmation" class="form-label">Confirme a Senha</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Repita a senha" required>
            <button class="btn btn-outline-secondary" type="button"
                    onclick="togglePassword('password_confirmation','password-icon-confirm')">
              <i id="password-icon-confirm" class="bi bi-eye"></i>
            </button>
          </div>
          <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
      </div>

      <div class="cta-wrap mt-4">
        <button type="submit" class="btn btn-success btn-lg btn-cta">Criar Conta</button>
      </div>

      <p class="mt-3 text-center">
        <a class="text-decoration-none login-link" href="<?php echo e(route('login')); ?>">Já tem uma conta? Entrar</a>
      </p>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword(inputId, iconId){
      const input = document.getElementById(inputId);
      const icon  = document.getElementById(iconId);
      const isPwd = input.type === "password";
      input.type  = isPwd ? "text" : "password";
      icon.classList.toggle("bi-eye", !isPwd);
      icon.classList.toggle("bi-eye-slash", isPwd);
    }
  </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/auth/register.blade.php ENDPATH**/ ?>