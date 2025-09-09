<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AgroConecta - @yield('title', 'Página')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        body {
            background-image: url('{{ asset("images/background2.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding-top: 70px;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            background-color: rgba(120, 123, 123, 0.9);
            box-shadow: 0 4px 10px rgba(0,0,0,.1);
            opacity: 0.95;
        }

        .navbar .nav-link, .navbar .navbar-brand {
            color: #fff;
        }
        .navbar .nav-link:hover {
            color: #ddd; text-decoration: underline;
        }

        .navbar-dark .navbar-toggler {
            border-color: rgba(255,255,255,.25);
        }
        .navbar-dark .navbar-toggler-icon {
            background-image: var(--bs-navbar-toggler-icon-bg);
        }

        @media (max-width: 991.98px) {
            .navbar-nav .nav-link { padding: .5rem 1rem; }
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
            background-color: rgba(120, 123, 123, 0.9);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            opacity: 0.9;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            text-decoration: underline;
            color: #ccc;
        }

        .content-box {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
            max-width: 1200px;
            width: 100%;
            margin: 50px auto;
            max-height: 80vh;
            overflow-y: auto;
        }

        .content-box::-webkit-scrollbar {
            width: 35px;
        }

        .content-box::-webkit-scrollbar-track {
            background: rgba(245, 245, 245, 0.9);
            border-radius: 20px;
        }

        .content-box::-webkit-scrollbar-thumb {
            background-color: rgba(120, 120, 120, 0.6);
            border-radius: 20px;
        }

        .content-box::-webkit-scrollbar-thumb:hover {
            background-color: rgba(100, 100, 100, 0.9);
        }

        .btn-voltar{
            background: #fff;
            color: #111827;
            border: 1px solid rgba(0,0,0,.12);
            border-radius: 10px;
            padding: 8px 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: background .15s ease, color .15s ease, transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }
        .btn-voltar:hover{
            background: rgba(25,135,84,.10);
            color: #198754;
            border-color: rgba(25,135,84,.30);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(17,24,39,.08);
            text-decoration: none;
        }
        .btn-voltar:focus-visible{
            outline: 2px solid rgba(25,135,84,.35);
            outline-offset: 2px;
        }
        .btn-voltar .bi{ font-size: 1rem; }</style>
    @stack('styles')
</head>
<body>

   <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">AgroConecta</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-lg-auto my-2 my-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('products.show') }}">Produtos</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('sell.cadastroProduto') }}">Vender</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('chat.inbox') }}">Mensagens</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('cart.view') }}">Carrinho</a></li>
            </ul>

            <ul class="navbar-nav ms-lg-auto">
                <li class="nav-item"><a class="nav-link px-lg-3" href="{{ route('minha.conta') }}">Minha Conta</a></li>
                <li class="nav-item">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sair
                    </a>
                </form>
                </li>
            </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @hasSection('boxed')
            <div class="content-box">
                @yield('content')
            </div>
        @else
            @yield('content')
        @endif
    </div> 

    @stack('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
    //limpa overlays e body travado
    document.addEventListener('hidden.bs.modal', () => {
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');        
  });
    </script>

     @stack('scripts')


    
</body>
</html>
