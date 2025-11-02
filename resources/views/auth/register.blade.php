@extends('layouts.app')

@section('title', 'Criar conta')
@section('boxed', false)

@section('content')
@php
  $cities = [
    'Chapecó','Xanxerê','Xaxim','Pinhalzinho','Palmitos','Maravilha','Modelo','Saudades','Águas de Chapecó',
    'Nova Erechim','Nova Itaberaba','Coronel Freitas','Quilombo','Abelardo Luz','Coronel Martins','Galvão',
    'São Lourenço do Oeste','Campo Erê','Saltinho','São Domingos','Ipuaçu','Entre Rios','Jupiá',
    'Itapiranga','Iporã do Oeste','Mondaí','Riqueza','Descanso','Tunápolis','Belmonte','Paraíso',
    'São Miguel do Oeste','Guaraciaba','Anchieta','Dionísio Cerqueira','Barra Bonita'
  ];
@endphp

<div class="row justify-content-center my-5">
  <div class="col-lg-8 col-xl-7">
    <div class="card border-0 shadow-sm">
      <div class="card-body p-4 p-md-5">

        <div class="text-center mb-4">
          <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center" style="width:60px;height:60px;">
            <i class="bi bi-person-plus fs-3"></i>
          </div>
          <h2 class="fw-semibold mt-3 mb-1">Criar sua conta</h2>
          <p class="text-muted mb-0">Cadastre-se para comprar e vender no <strong>AgroConecta</strong>.</p>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger py-2">
            <ul class="mb-0 small">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('register') }}" method="POST" autocomplete="off">
          @csrf
          <div class="row g-3">

            {{-- Nome --}}
            <div class="col-md-6">
              <label for="name" class="form-label">Nome completo</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                <input type="text" id="name" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Seu nome completo" required>
              </div>
              @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Email --}}
            <div class="col-md-6">
              <label for="email" class="form-label">E-mail</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                <input type="email" id="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="seu@email.com" required>
              </div>
              @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Telefone --}}
            <div class="col-md-6">
              <label for="phone" class="form-label">Telefone</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
                <input type="text" id="phone" name="phone"
                       class="form-control @error('phone') is-invalid @enderror"
                       value="{{ old('phone') }}" placeholder="(49) 99999-9999" required>
              </div>
              @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Endereço --}}
            <div class="col-md-6">
              <label for="address" class="form-label">Endereço</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                <input type="text" id="address" name="address"
                       class="form-control @error('address') is-invalid @enderror"
                       value="{{ old('address') }}" placeholder="Bairro, rua, número..." required>
              </div>
              @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Cidade --}}
            <div class="col-md-6">
              <label for="city" class="form-label">Cidade</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-buildings"></i></span>
                <select id="city" name="city"
                        class="form-select @error('city') is-invalid @enderror" required>
                  <option value="" disabled {{ old('city') ? '' : 'selected' }}>Selecione...</option>
                  @foreach($cities as $c)
                    <option value="{{ $c }}" @selected(old('city') === $c)>{{ $c }}</option>
                  @endforeach
                </select>
              </div>
              @error('city') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Senha --}}
            <div class="col-md-6">
              <label for="password" class="form-label">Senha</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="mínimo 8 caracteres" required>
                <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePassword('password','password-icon')">
                  <i id="password-icon" class="bi bi-eye"></i>
                </button>
              </div>
              @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>

            {{-- Confirmar senha --}}
            <div class="col-md-6">
              <label for="password_confirmation" class="form-label">Confirmar senha</label>
              <div class="input-group">
                <span class="input-group-text bg-white"><i class="bi bi-shield-lock"></i></span>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control @error('password_confirmation') is-invalid @enderror" required>
                <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePassword('password_confirmation','password-icon-confirm')">
                  <i id="password-icon-confirm" class="bi bi-eye"></i>
                </button>
              </div>
              @error('password_confirmation') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-success px-4">
              <i class="bi bi-person-check me-1"></i> Criar conta
            </button>
          </div>

          <p class="text-center mt-4 mb-0 text-muted">
            Já tem conta?
            <a href="{{ route('login') }}" class="text-success text-decoration-none fw-semibold">
              Entrar
            </a>
          </p>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  function togglePassword(inputId, iconId) {
      const input = document.getElementById(inputId);
      const icon  = document.getElementById(iconId);
      if (!input || !icon) return;
      const isPass = input.type === 'password';
      input.type = isPass ? 'text' : 'password';
      icon.classList.toggle('bi-eye', !isPass);
      icon.classList.toggle('bi-eye-slash', isPass);
  }
</script>
@endpush
