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
        body{
            background-image: url('{{ asset("images/background3.jpg") }}');
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            margin: 0;
            padding-top: 70px;
            }

            .navbar{
                position: fixed;
                top: 0; width: 100%; z-index: 1030;

                background-color: rgba(120,123,123,0.9);
                box-shadow: 0 4px 10px rgba(0,0,0,.1);
                opacity: .95;
            }
                .navbar .navbar-brand,
                .navbar .nav-link{
                color: #fff;
            }
                .navbar .nav-link:hover,
                .navbar .navbar-brand:hover{
                color: #ddd;
                text-decoration: underline;
            }

            .navbar .container-fluid{ position: relative; }
            .navbar .nav-center{
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: .75rem;
                white-space: nowrap;
            }

            .navbar-dark .navbar-toggler{ border-color: rgba(255,255,255,.25); }
            .navbar-dark .navbar-toggler-icon{ background-image: var(--bs-navbar-toggler-icon-bg); }

            @media (max-width: 991.98px){
            .navbar .nav-center{
                position: static;
                transform: none;
                width: 100%;
                justify-content: center;
            }
            .navbar-nav .nav-link{ padding: .5rem 1rem; }
            }

            .content-box{
                background: rgba(255,255,255,.85);
                backdrop-filter: blur(5px);
                border-radius: 20px;
                padding: 40px;
                box-shadow: 0 6px 15px rgba(0,0,0,.3);
                max-width: 1200px; width: 100%;
                margin: 50px auto;
                max-height: 80vh; overflow-y: auto;
                }
                /* scrollbars */
            .content-box::-webkit-scrollbar{ width: 35px; }
            .content-box::-webkit-scrollbar-track{ background: rgba(245,245,245,.9); border-radius: 20px; }
            .content-box::-webkit-scrollbar-thumb{ background-color: rgba(120,120,120,.6); border-radius: 20px; }
            .content-box::-webkit-scrollbar-thumb:hover{ background-color: rgba(100,100,100,.9); }

                /* Botão voltar */
                .btn-voltar{
                background:#fff; color:#111827; border:1px solid rgba(0,0,0,.12);
                border-radius:10px; padding:8px 12px; font-weight:500;
                display:inline-flex; align-items:center; gap:8px; text-decoration:none;
                transition: background .15s, color .15s, transform .15s, box-shadow .15s, border-color .15s;
                }
            .btn-voltar:hover{
                background: rgba(25,135,84,.1); color:#198754; border-color: rgba(25,135,84,.3);
                transform: translateY(-1px); box-shadow:0 6px 16px rgba(17,24,39,.08);
                }
            .btn-voltar:focus-visible{ outline:2px solid rgba(25,135,84,.35); outline-offset:2px; }
            .btn-voltar .bi{ font-size:1rem; }

                /* Footer */
            .site-footer{ background: rgba(33,37,41,.92); color:#f8f9fa; border-top:1px solid rgba(255,255,255,.08); }
            .site-footer a{ color:#e2e6ea; text-decoration:none; }
            .site-footer a:hover{ color:#fff; text-decoration:underline; }
            .site-footer__brand{ display:flex; align-items:center; gap:.75rem; }
            .site-footer__logo{ width:36px; height:36px; border-radius:10px; object-fit:cover; }
            .site-footer__muted{ color:#cfd4da; }
            .site-footer__divider{ border-top:1px solid rgba(255,255,255,.08); margin-top:.75rem; }
            .social a{
                width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;
                border:1px solid rgba(255,255,255,.12); border-radius:10px; background:transparent;
                transition: transform .15s, box-shadow .15s, border-color .15s;
            }
            .social a:hover{
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(0,0,0,.15);
                border-color: rgba(255,255,255,.3);
            }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

   <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold" href="{{ route('dashboard') }}">AgroConecta</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav nav-center my-2 my-lg-0">
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

    <main class="flex-fill">
        <div class="container">
            @hasSection('boxed')
                <div class="content-box">
                    @yield('content')
                </div>
            @else
                @yield('content')
            @endif
        </div>
    </main>

    @stack('modals')

    <footer class="site-footer mt-auto pt-5 pb-4" role="contentinfo">
        <div class="container">
            <div class="row g-4">
            {{-- Coluna 1 --}}
            <div class="col-12 col-lg-5">
                <div class="site-footer__brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo AgroConecta" class="site-footer__logo" loading="lazy">
                <div>
                    <strong>AgroConecta</strong><br>
                    <small class="site-footer__muted">Conectando agricultores locais e consumidores.</small>
                </div>
                </div>


                <p class="mt-3 mb-2 site-footer__muted">
                Nossa missão é aproximar quem produz com quem consome, fortalecendo a economia local e a agricultura sustentável.
                </p>

                <div class="d-flex align-items-center gap-2 social" aria-label="Redes sociais">
                <a href="https://instagram.com" target="_blank" rel="noopener" aria-label="Instagram">
                    <i class="bi bi-instagram"></i>
                </a>
                <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="https://wa.me/5599999999999" target="_blank" rel="noopener" aria-label="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="mailto:suporte.agroconecta@gmail.com" aria-label="E-mail">
                    <i class="bi bi-envelope"></i>
                </a>
                </div>
            </div>

            {{-- Coluna 2 --}}
            <div class="col-6 col-lg-3">
                <h6 class="text-uppercase mb-3">Navegar</h6>
                <ul class="list-unstyled mb-0">
                <li><a href="{{ route('dashboard') }}">Início</a></li>
                <li><a href="{{ route('products.show') }}">Produtos</a></li>
                <li><a href="{{ route('sell.cadastroProduto') }}">Vender</a></li>
                <li><a href="{{ route('chat.inbox') }}">Mensagens</a></li>
                <li><a href="{{ route('cart.view') }}">Carrinho</a></li>
                <li><a href="{{ route('minha.conta') }}">Minha Conta</a></li>
                </ul>
            </div>

            {{-- Coluna 3 --}}
            <div class="col-6 col-lg-3">
                <h6 class="text-uppercase mb-3">Navegar</h6>
                <ul class="list-unstyled mb-0">
                <li><a href="{{ route('orders.index') }}">Meus Pedidos</a></li>
                <li><a href="{{ route('user.data') }}">Meus Dados</a></li>
                <li><a href="{{ route('account.myProducts') }}">Meus Produtos</a></li>
                <li><a href="{{ route('account.myRatings') }}">Avaliações</a></li>
                <li><a href="{{ route('seller.mySales') }}">Minhas Vendas</a></li>
                <li><a href="{{ route('support') }}">Suporte</a></li>
                </ul>
            </div>
            </div>

            <div class="site-footer__divider"></div>
            <div class="mt-3 small text-center text-md-start">
            © {{ date('Y') }} AgroConecta. Todos os direitos reservados.
            </div>

            @stack('footer')
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener('hidden.bs.modal', () => {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('padding-right');
      });
    </script>

     @stack('scripts')
</body>
</html>