@extends('layouts.app')

@section('title', 'Minhas Vendas')
@section('boxed', true)

@section('content')
  <h1 class="text-center mb-4">Minhas Vendas</h1>

  @php
      $base = $vendas;
      $porDia = $base

        ->groupBy(fn($v) => optional($v->created_at)->toDateString())
        ->sortKeys()
        ->map(function($items, $ymd){
            $vendasDia  = (float) $items->sum('total_price');
            $pedidosDia = (int)   $items->count();
            return [
                'label'=> \Carbon\Carbon::parse($ymd)->format('d/m'),
                'vendas'=> $vendasDia,
                'pedidos'=> $pedidosDia,
                'aov'=> $pedidosDia ? $vendasDia / $pedidosDia : 0,
            ];
        });

      $labelsDias= $porDia->pluck('label')->values();
      $serieVendas = $porDia->pluck('vendas')->values();
      $seriePedidos = $porDia->pluck('pedidos')->values();
      $serieAov = $porDia->pluck('aov')->values();

      // Top 5 produtos por unidades
      $topProdutosQty = $base->groupBy('product_id')->map(function($items){
              $p = $items->first();
              return [
                  'nome'     => optional($p->product)->name ?? 'Produto',
                  'unidades' => (int) $items->sum('quantity'),
              ];
          })
          ->sortByDesc('unidades')
          ->take(5);

      $labelsProdutosQty = $topProdutosQty->pluck('nome')->values();
      $serieUnidadesProdutos = $topProdutosQty->pluck('unidades')->values();

      // Distribuição por status
      $porStatus = $vendas->groupBy('status')->map->count()->sortDesc();
      $labelsStatus = $porStatus->keys()->values();
      $serieStatus = $porStatus->values();

      // Pedidos por hora
      $porHoraRaw = $base->groupBy(fn($v) => optional($v->created_at)->format('H'))->map->count();
      $labelsHoras = collect(range(0,23))->map(fn($h) => str_pad($h,2,'0',STR_PAD_LEFT).'h');
      $seriePedidosHora = collect(range(0,23))->map(function($h) use ($porHoraRaw){
          $key = str_pad($h,2,'0',STR_PAD_LEFT);
          return (int) ($porHoraRaw[$key] ?? 0);
      });

      // Cards
      $totalVendas = (float) $base->sum('total_price');
      $totalPedidos = (int)   $base->count();
      $ticketMedio = $totalPedidos ? $totalVendas / $totalPedidos : 0;
      $pagos = (int)   $vendas->where('status','Pago')->count();
      $pctPagos = $vendas->count() ? ($pagos / $vendas->count()) * 100 : 0;

      $fmtBRL = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.');
  @endphp

  {{-- Cards --}}
  <div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Vendas (período mostrado)</div>
      <div class="h4 mb-0">{{ $fmtBRL($totalVendas) }}</div>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Pedidos</div>
      <div class="h4 mb-0">{{ $totalPedidos }}</div>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Ticket médio</div>
      <div class="h4 mb-0">{{ $fmtBRL($ticketMedio) }}</div>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">% Pedidos pagos</div>
      <div class="h4 mb-0">{{ number_format($pctPagos, 1, ',', '.') }}%</div>
    </div></div></div>
  </div>

  {{-- Vendas x pedidos por dia + status --}}
  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Vendas (R$) × Pedidos por dia</div>
        <div class="card-body"><canvas id="chartVendasPedidos" style="height:320px"></canvas></div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Status dos pedidos</div>
        <div class="card-body"><canvas id="chartStatus" style="height:320px"></canvas></div>
      </div>
    </div>
  </div>

  {{-- Ticket medio por dia + pedidos por horario --}}
  <div class="row g-4 mb-4">
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Ticket médio por dia</div>
        <div class="card-body"><canvas id="chartAovDia" style="height:300px"></canvas></div>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Distribuição de pedidos por horário</div>
        <div class="card-body"><canvas id="chartPedidosHora" style="height:300px"></canvas></div>
      </div>
    </div>
  </div>

  {{-- Top 5 produtos por unidades --}}
  <div class="row g-4 mb-4">
    <div class="col-lg-12">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Top 5 produtos por unidades vendidas</div>
        <div class="card-body"><canvas id="chartTopProdutosQty" style="height:320px"></canvas></div>
      </div>
    </div>
  </div>

  @if ($vendas->isEmpty())
    <p class="text-center">Você ainda não possui vendas cadastradas.</p>
  @endif

  <a href="{{ route('minha.conta') }}" class="btn btn-dark mt-3">
    <i class="bi bi-arrow-left"></i> Voltar
  </a>
@endsection

@push('scripts')
  {{-- Dados renderizados pelo Blade em JSON --}}
  <script id="sales-data" type="application/json">
    {
      "labelsDias": @json($labelsDias),
      "serieVendas": @json($serieVendas),
      "seriePedidos": @json($seriePedidos),
      "serieAov": @json($serieAov),

      "labelsProdutosQty": @json($labelsProdutosQty),
      "serieUnidadesProdutos": @json($serieUnidadesProdutos),

      "labelsStatus": @json($labelsStatus),
      "serieStatus": @json($serieStatus),

      "labelsHoras": @json($labelsHoras),
      "seriePedidosHora": @json($seriePedidosHora)
    }
  </script>


  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>

  <script src="{{ asset('js/account.mySales.js') }}" defer></script>
@endpush

