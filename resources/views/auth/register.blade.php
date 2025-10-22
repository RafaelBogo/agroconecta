<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AgroConecta - Registro</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"/>
  <link rel="stylesheet" href="{{ asset('css/auth.register.css') }}">

</head>
<body>
  <div class="bg-overlay"></div>

  <div class="card-glass p-4 p-md-5 mx-3">
    <h1 class="title h3 text-center mb-4">Criar sua conta</h1>

    @if ($errors->any())
      <div class="alert alert-danger py-2">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @php
      $cities = [
        'Chapecó','Xanxerê','Xaxim','Pinhalzinho','Palmitos','Maravilha','Modelo','Saudades','Águas de Chapecó',
        'Nova Erechim','Nova Itaberaba','Coronel Freitas','Quilombo','Abelardo Luz','Coronel Martins','Galvão',
        'São Lourenço do Oeste','Campo Erê','Saltinho','São Domingos','Ipuaçu','Entre Rios','Jupiá',
        'Itapiranga','Iporã do Oeste','Mondaí','Riqueza','Descanso','Tunápolis','Belmonte','Paraíso',
        'São Miguel do Oeste','Guaraciaba','Anchieta','Dionísio Cerqueira','Barra Bonita'
      ];
    @endphp

    <form action="{{ route('register') }}" method="POST">
      @csrf

      <div class="row grid">
        <div class="col-md-6">
          <label for="name" class="form-label">Nome Completo</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person"></i></span>
            <input type="text" id="name" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Seu nome completo" value="{{ old('name') }}" required>
          </div>
          @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="email" class="form-label">Email</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" id="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="voce@exemplo.com" value="{{ old('email') }}" required>
          </div>
          @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="phone" class="form-label">Telefone</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
            <input type="text" id="phone" name="phone"
                   class="form-control @error('phone') is-invalid @enderror"
                   placeholder="(00) 00000-0000" value="{{ old('phone') }}">
          </div>
          @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="address" class="form-label">Endereço Completo</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
            <input id="address" name="address"
                   class="form-control @error('address') is-invalid @enderror"
                   placeholder="Bairro, rua, referência..." value="{{ old('address') }}" required>
          </div>
          @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        
        <div class="col-md-6">
          <label for="city" class="form-label">Cidade (Oeste Catarinense)</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-buildings"></i></span>
            <select id="city" name="city"
                    class="form-select @error('city') is-invalid @enderror" required>
              <option value="" disabled {{ old('city') ? '' : 'selected' }}>Selecione sua cidade</option>
              @foreach ($cities as $c)
                <option value="{{ $c }}" {{ old('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
              @endforeach
            </select>
          </div>
          @error('city') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="password" class="form-label">Senha</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" id="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Mínimo 8 caracteres" required>
            <button class="btn btn-outline-secondary" type="button"
                    onclick="togglePassword('password','password-icon')">
              <i id="password-icon" class="bi bi-eye"></i>
            </button>
          </div>
          @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label for="password_confirmation" class="form-label">Confirme a Senha</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="Repita a senha" required>
            <button class="btn btn-outline-secondary" type="button"
                    onclick="togglePassword('password_confirmation','password-icon-confirm')">
              <i id="password-icon-confirm" class="bi bi-eye"></i>
            </button>
          </div>
          @error('password_confirmation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="cta-wrap mt-4">
        <button type="submit" class="btn btn-success btn-lg btn-cta">Criar Conta</button>
      </div>

      <p class="mt-3 text-center">
        <a class="text-decoration-none login-link" href="{{ route('login') }}">Já tem uma conta? Entrar</a>
      </p>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('js/auth.register.js') }}" defer></script>

</body>
</html>