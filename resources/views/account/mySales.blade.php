@extends('layouts.app')

@section('title', 'Minhas Vendas')
@section('boxed', true)

@section('content')
    <h1 class="text-center mb-4">Minhas Vendas</h1>

    @php
        $porDia = $vendas->groupBy(fn($v) => optional($v->created_at)->format('d/m'))
            ->map(function($items){
                $vendasDia  = (float) $items->sum('total_price');
                $pedidosDia = (int) $items->count();
                return [
                    'vendas'  => $vendasDia,
                    'pedidos' => $pedidosDia,
                    'aov'     => $pedidosDia ? $vendasDia / $pedidosDia : 0,
                ];
            })
            ->sortKeys();

        $labelsDias   = $porDia->keys()->values();
        $serieVendas  = $porDia->pluck('vendas')->values();
        $seriePedidos = $porDia->pluck('pedidos')->values();
        $serieAov     = $porDia->pluck('aov')->values();

        // Top 5 produtos
        $topProdutosQty = $vendas->groupBy('product_id')->map(function($items){
                $p = $items->first();
                return [
                    'nome'     => optional($p->product)->name ?? 'Produto',
                    'unidades' => (int) $items->sum('quantity'),
                ];
            })
            ->sortByDesc('unidades')
            ->take(5);

        $labelsProdutosQty   = $topProdutosQty->pluck('nome')->values();
        $serieUnidadesProdutos = $topProdutosQty->pluck('unidades')->values();

        // Distribuição por status
        $porStatus   = $vendas->groupBy('status')->map->count()->sortDesc();
        $labelsStatus= $porStatus->keys()->values();
        $serieStatus = $porStatus->values();

        // Pedidos por hora
        $porHoraRaw = $vendas->groupBy(fn($v) => optional($v->created_at)->format('H'))->map->count();
        $labelsHoras = collect(range(0,23))->map(fn($h) => str_pad($h,2,'0',STR_PAD_LEFT).'h');
        $seriePedidosHora = collect(range(0,23))->map(function($h) use ($porHoraRaw){
            $key = str_pad($h,2,'0',STR_PAD_LEFT);
            return (int) ($porHoraRaw[$key] ?? 0);
        });

        // Cards
        $totalVendas  = (float) $vendas->sum('total_price');
        $totalPedidos = (int) $vendas->count();
        $ticketMedio  = $totalPedidos ? $totalVendas / $totalPedidos : 0;
        $pagos        = (int) $vendas->where('status','Pago')->count();
        $pctPagos     = $totalPedidos ? ($pagos / $totalPedidos) * 100 : 0;

        $fmtBRL = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.');
    @endphp

    {{-- Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm"><div class="card-body">
                <div class="text-muted small">Vendas (período mostrado)</div>
                <div class="h4 mb-0">{{ $fmtBRL($totalVendas) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm"><div class="card-body">
                <div class="text-muted small">Pedidos</div>
                <div class="h4 mb-0">{{ $totalPedidos }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm"><div class="card-body">
                <div class="text-muted small">Ticket médio</div>
                <div class="h4 mb-0">{{ $fmtBRL($ticketMedio) }}</div>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm"><div class="card-body">
                <div class="text-muted small">% Pedidos pagos</div>
                <div class="h4 mb-0">{{ number_format($pctPagos, 1, ',', '.') }}%</div>
            </div></div>
        </div>
    </div>

    {{-- Vendas e Pedidos por dia + rosca --}}
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

    {{-- Ticket médio por dia  +  Pedidos por horário --}}
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

    {{-- Top 5 produtos --}}
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const labelsDias   = @json($labelsDias);
    const serieVendas  = @json($serieVendas);
    const seriePedidos = @json($seriePedidos);
    const serieAov     = @json($serieAov);

    const labelsProdutosQty   = @json($labelsProdutosQty);
    const serieUnidadesProdutos = @json($serieUnidadesProdutos);

    const labelsStatus = @json($labelsStatus);
    const serieStatus  = @json($serieStatus);

    const labelsHoras  = @json($labelsHoras);
    const seriePedidosHora = @json($seriePedidosHora);

    // Paleta
    const green  = 'rgba(25, 135, 84, 0.9)';
    const greenL = 'rgba(25, 135, 84, 0.25)';
    const blue   = 'rgba(13, 110, 253, 0.9)';
    const blueL  = 'rgba(13, 110, 253, 0.2)';
    const gray   = '#6c757d';
    const colors = ['#198754','#0d6efd','#ffc107','#dc3545','#6f42c1','#20c997','#fd7e14',gray];

    // Vendas x Pedidos por dia
    new Chart(document.getElementById('chartVendasPedidos'), {
        type: 'bar',
        data: {
            labels: labelsDias,
            datasets: [
                {
                    type: 'line',
                    label: 'Vendas (R$)',
                    data: serieVendas,
                    yAxisID: 'y',
                    borderColor: green,
                    backgroundColor: greenL,
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 0
                },
                {
                    type: 'bar',
                    label: 'Pedidos',
                    data: seriePedidos,
                    yAxisID: 'y1',
                    backgroundColor: blueL,
                    borderColor: blue,
                    borderWidth: 1,
                    borderRadius: 6,
                    maxBarThickness: 24,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    position: 'left', beginAtZero: true,
                    ticks: { callback: v => v.toLocaleString('pt-BR', {style:'currency', currency:'BRL'}) },
                    grid: { drawOnChartArea: false }
                },
                y1: { position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } },
                x: { grid: { display: false } }
            },
            plugins: {
                legend: { labels: { usePointStyle: true } },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.dataset.label.includes('Vendas')
                          ? `${ctx.dataset.label}: ${ctx.raw.toLocaleString('pt-BR',{style:'currency',currency:'BRL'})}`
                          : `${ctx.dataset.label}: ${ctx.raw}`
                    }
                }
            }
        }
    });

    // Ticket médio por dia
    new Chart(document.getElementById('chartAovDia'), {
        type: 'line',
        data: { labels: labelsDias, datasets: [{
            label: 'Ticket médio (R$)',
            data: serieAov,
            borderColor: blue,
            backgroundColor: blueL,
            borderWidth: 2, tension: 0.3, pointRadius: 2
        }]},
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: v => v.toLocaleString('pt-BR', {style:'currency', currency:'BRL'}) }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Pedidos por horário
    new Chart(document.getElementById('chartPedidosHora'), {
        type: 'bar',
        data: { labels: labelsHoras, datasets: [{
            label: 'Pedidos',
            data: seriePedidosHora,
            backgroundColor: greenL,
            borderColor: green,
            borderWidth: 1.5,
            borderRadius: 6
        }]},
        options: {
            responsive: true, maintainAspectRatio: false,
            scales: { y: { beginAtZero: true }, x: { grid: { display: false } } },
            plugins: { legend: { display: false } }
        }
    });

    // Top 5 produtos por unidades
    new Chart(document.getElementById('chartTopProdutosQty'), {
        type: 'bar',
        data: {
            labels: labelsProdutosQty,
            datasets: [{
                label: 'Unidades',
                data: serieUnidadesProdutos,
                backgroundColor: blueL,
                borderColor: blue,
                borderWidth: 1.5,
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            scales: { x: { beginAtZero: true, grid: { drawBorder: false } }, y: { grid: { display: false } } },
            plugins: { legend: { display: false } }
        }
    });

    // Rosca de status
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: labelsStatus,
            datasets: [{
                data: serieStatus,
                backgroundColor: labelsStatus.map((_, i) => colors[i % colors.length]),
                borderColor: '#fff', borderWidth: 1
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            cutout: '60%'
        }
    });
</script>
@endpush
