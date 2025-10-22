<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Conta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.verify.css') }}">
    
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center h-100">
        <div class="verification-box">
            <h4>Verificar Conta</h4>

            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('verify') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="verification_code" class="form-label">Código de Verificação</label>
                    <input type="text" class="form-control @error('verification_code') is-invalid @enderror" id="verification_code" name="verification_code" placeholder="Digite seu código" required>
                    @error('verification_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success w-100">Verificar</button>
            </form>
        </div>
    </div>
</body>
</html>
