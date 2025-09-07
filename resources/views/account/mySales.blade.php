@extends('layouts.app')

@section('title', 'Minhas Vendas')
@section('boxed', true)

@section('content')
    <h1 class="text-center mb-4">Minhas Vendas</h1>

    @php
        // Agregações rapidas a partir de $vendas

        // por dia
        $porDia = $vendas->groupBy(fn($v) => optional($v->created_at)->format('d/m'))
            ->map(fn($items) => [
                'gmv'    => (float) $items->sum('total_price'),
                'pedidos'=> (int)   $items->count(),
            ])->sortKeys();

        $labelsDias   = $porDia->keys()->values();
        $serieGMV     = $porDia->pluck('gmv')->values();
        $seriePedidos = $porDia->pluck('pedidos')->values();

        // Top produtos GMV
        $topProdutos = $vendas->groupBy('product_id')->map(function($items){
                $primeiro = $items->first();
                return [
                    'nome' => optional($primeiro->product)->name ?? 'Produto',
                    'gmv'  => (float) $items->sum('total_price'),
                ];
            })
            ->sortByDesc('gmv')
            ->take(5);

        $labelsProdutos = $topProdutos->pluck('nome')->values();
        $serieGMVProdutos = $topProdutos->pluck('gmv')->values();

        // Distribuição por status
        $porStatus = $vendas->groupBy('status')->map->count()->sortDesc();
        $labelsStatus = $porStatus->keys()->values();
        $serieStatus  = $porStatus->values();

        // Formatadores
        $fmtBRL = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.');
    @endphp

    {{-- Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">GMV (período mostrado)</div>
                    <div class="h4 mb-0">{{ $fmtBRL($vendas->sum('total_price')) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Pedidos</div>
                    <div class="h4 mb-0">{{ $vendas->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @php $aov = $vendas->count() ? $vendas->sum('total_price') / $vendas->count() : 0; @endphp
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Ticket médio</div>
                    <div class="h4 mb-0">{{ $fmtBRL($aov) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            @php
                $pagos = $vendas->where('status', 'Pago')->count();
                $conv  = $vendas->count() ? ($pagos / $vendas->count()) * 100 : 0;
            @endphp
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">% Pedidos pagos</div>
                    <div class="h4 mb-0">{{ number_format($conv, 1, ',', '.') }}%</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">GMV x Pedidos por dia</div>
                <div class="card-body">
                    <canvas id="chartGmvPedidos" style="height:320px"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Status dos pedidos</div>
                <div class="card-body">
                    <canvas id="chartStatus" style="height:320px"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">Top 5 produtos por GMV</div>
                <div class="card-body">
                    <canvas id="chartTopProdutos" style="height:320px"></canvas>
                </div>
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
        // Dados vindos do PHP
        const labelsDias   = @json($labelsDias);
        const serieGMV     = @json($serieGMV);
        const seriePedidos = @json($seriePedidos);

        const labelsProdutos    = @json($labelsProdutos);
        const serieGMVProdutos  = @json($serieGMVProdutos);

        const labelsStatus = @json($labelsStatus);
        const serieStatus  = @json($serieStatus);

        // Paletas simples
        const green  = 'rgba(25, 135, 84, 0.9)';
        const greenL = 'rgba(25, 135, 84, 0.25)';
        const blue   = 'rgba(13, 110, 253, 0.9)';
        const blueL  = 'rgba(13, 110, 253, 0.2)';
        const colors = ['#198754','#0d6efd','#ffc107','#dc3545','#6f42c1','#20c997','#fd7e14','#6c757d'];

        // GMV por pedidos
        const ctxGmv = document.getElementById('chartGmvPedidos');
        new Chart(ctxGmv, {
            type: 'bar',
            data: {
                labels: labelsDias,
                datasets: [
                    {
                        type: 'line',
                        label: 'GMV (R$)',
                        data: serieGMV,
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
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: {
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: (v) => v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })
                        },
                        grid: { drawOnChartArea: false }
                    },
                    y1: {
                        position: 'right',
                        beginAtZero: true,
                        grid: { drawOnChartArea: false }
                    },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { labels: { usePointStyle: true } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                if (ctx.dataset.label.includes('GMV')) {
                                    return `${ctx.dataset.label}: ${ctx.raw.toLocaleString('pt-BR', {style:'currency', currency:'BRL'})}`;
                                }
                                return `${ctx.dataset.label}: ${ctx.raw}`;
                            }
                        }
                    }
                }
            }
        });

        // Top 5 produtos
        const ctxTop = document.getElementById('chartTopProdutos');
        new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: labelsProdutos,
                datasets: [{
                    label: 'GMV (R$)',
                    data: serieGMVProdutos,
                    backgroundColor: greenL,
                    borderColor: green,
                    borderWidth: 1.5,
                    borderRadius: 8,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { callback: (v) => v.toLocaleString('pt-BR', { style:'currency', currency:'BRL' }) },
                        grid: { drawBorder: false }
                    },
                    y: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.raw.toLocaleString('pt-BR', {style:'currency', currency:'BRL'})
                        }
                    }
                }
            }
        });

        // Rosca de status
        const ctxStatus = document.getElementById('chartStatus');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: labelsStatus,
                datasets: [{
                    data: serieStatus,
                    backgroundColor: labelsStatus.map((_, i) => colors[i % colors.length]),
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '60%'
            }
        });
    </script>
@endpush
