<?php $__env->startSection('title', 'Minhas Vendas'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
  <h1 class="text-center mb-4">Minhas Vendas</h1>

  <?php
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
  ?>

  
  <div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Vendas (período mostrado)</div>
      <div class="h4 mb-0"><?php echo e($fmtBRL($totalVendas)); ?></div>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Pedidos</div>
      <div class="h4 mb-0"><?php echo e($totalPedidos); ?></div>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">Ticket médio</div>
      <div class="h4 mb-0"><?php echo e($fmtBRL($ticketMedio)); ?></div>
    </div></div></div>

    <div class="col-md-3"><div class="card shadow-sm"><div class="card-body">
      <div class="text-muted small">% Pedidos pagos</div>
      <div class="h4 mb-0"><?php echo e(number_format($pctPagos, 1, ',', '.')); ?>%</div>
    </div></div></div>
  </div>

  
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

  
  <div class="row g-4 mb-4">
    <div class="col-lg-12">
      <div class="card shadow-sm">
        <div class="card-header fw-semibold">Top 5 produtos por unidades vendidas</div>
        <div class="card-body"><canvas id="chartTopProdutosQty" style="height:320px"></canvas></div>
      </div>
    </div>
  </div>

  <?php if($vendas->isEmpty()): ?>
    <p class="text-center">Você ainda não possui vendas cadastradas.</p>
  <?php endif; ?>

  <a href="<?php echo e(route('minha.conta')); ?>" class="btn btn-dark mt-3">
    <i class="bi bi-arrow-left"></i> Voltar
  </a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  
  <script id="sales-data" type="application/json">
    {
      "labelsDias": <?php echo json_encode($labelsDias, 15, 512) ?>,
      "serieVendas": <?php echo json_encode($serieVendas, 15, 512) ?>,
      "seriePedidos": <?php echo json_encode($seriePedidos, 15, 512) ?>,
      "serieAov": <?php echo json_encode($serieAov, 15, 512) ?>,

      "labelsProdutosQty": <?php echo json_encode($labelsProdutosQty, 15, 512) ?>,
      "serieUnidadesProdutos": <?php echo json_encode($serieUnidadesProdutos, 15, 512) ?>,

      "labelsStatus": <?php echo json_encode($labelsStatus, 15, 512) ?>,
      "serieStatus": <?php echo json_encode($serieStatus, 15, 512) ?>,

      "labelsHoras": <?php echo json_encode($labelsHoras, 15, 512) ?>,
      "seriePedidosHora": <?php echo json_encode($seriePedidosHora, 15, 512) ?>
    }
  </script>


  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>

  <script src="<?php echo e(asset('js/account.mySales.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/mySales.blade.php ENDPATH**/ ?>