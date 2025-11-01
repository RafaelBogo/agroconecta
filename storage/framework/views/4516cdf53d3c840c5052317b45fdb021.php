<?php $__env->startSection('title', 'Análise de Vendas'); ?>
<?php $__env->startSection('boxed', true); ?>

<?php $__env->startSection('content'); ?>
  <h1 class="mb-4">Minhas Vendas — Análise</h1>

  <?php if(session('success')): ?>
      <div class="alert alert-success"><?php echo e(session('success')); ?></div>
  <?php endif; ?>
  <?php if($errors->any()): ?>
      <div class="alert alert-danger"><?php echo e($errors->first()); ?></div>
  <?php endif; ?>

  <?php
      $base = $vendas;

      // totais
      $totalVendas  = (float) $base->sum('total_price');
      $totalPedidos = (int)   $base->count();
      $ticketMedio  = $totalPedidos ? $totalVendas / $totalPedidos : 0;
      $fmtBRL = fn($v) => 'R$ ' . number_format((float)$v, 2, ',', '.');

      // vendas por dia
      $porDia = $base
        ->groupBy(fn($v) => optional($v->created_at)->toDateString())
        ->sortKeys()
        ->map(function($items, $ymd){
            $vendasDia  = (float) $items->sum('total_price');
            return [
                'label'=> \Carbon\Carbon::parse($ymd)->format('d/m'),
                'vendas'=> $vendasDia,
            ];
        });
      $labelsDias  = $porDia->pluck('label')->values();
      $serieVendas = $porDia->pluck('vendas')->values();

      // ====== STATUS (incluindo Retirado) ======
      $porStatus = $base
        ->groupBy(function($v) {
            $s = $v->status ?? '';
            $s = trim($s);
            if ($s === '') {
                return 'Sem status';
            }
            // normaliza
            $s = mb_strtolower($s, 'UTF-8');
            return match ($s) {
                'retirado'  => 'Retirado',
                'concluido' => 'Concluido',
                'pendente'  => 'Pendente',
                'cancelado' => 'Cancelado',
                default     => ucfirst($s),
            };
        })
        ->map->count()
        ->sortDesc();

      $labelsStatus = $porStatus->keys()->values();
      $serieStatus  = $porStatus->values();

      // pedidos por hora (bem simples)
      $porHoraRaw = $base->groupBy(fn($v) => optional($v->created_at)->format('H'))->map->count();
      $labelsHoras = collect(range(0,23))->map(fn($h) => str_pad($h,2,'0',STR_PAD_LEFT));
      $serieHora   = collect(range(0,23))->map(function($h) use ($porHoraRaw){
          $key = str_pad($h,2,'0',STR_PAD_LEFT);
          return (int) ($porHoraRaw[$key] ?? 0);
      });
  ?>

  
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Total vendido</div>
          <div class="h4 mb-0"><?php echo e($fmtBRL($totalVendas)); ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Pedidos</div>
          <div class="h4 mb-0"><?php echo e($totalPedidos); ?></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Ticket médio</div>
          <div class="h4 mb-0"><?php echo e($fmtBRL($ticketMedio)); ?></div>
        </div>
      </div>
    </div>
  </div>

  
  <div class="row g-4 mb-4">
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white fw-semibold">Vendas por dia</div>
        <div class="card-body">
          <?php if($labelsDias->isEmpty()): ?>
            <p class="text-muted mb-0">Ainda não há vendas para montar o gráfico.</p>
          <?php else: ?>
            <canvas id="chartVendasDia" style="height: 240px;"></canvas>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white fw-semibold">Status dos pedidos</div>
        <div class="card-body">
          <?php if($labelsStatus->isEmpty()): ?>
            <p class="text-muted mb-0">Sem pedidos para exibir.</p>
          <?php else: ?>
            <canvas id="chartStatus" style="height: 240px;"></canvas>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold">Pedidos por horário</div>
    <div class="card-body">
      <canvas id="chartHora" style="height: 200px;"></canvas>
    </div>
  </div>

  
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold">Pedidos</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Comprador</th>
              <th>Itens</th>
              <th>Total</th>
              <th>Status</th>
              <th>MP Status</th>
              <th style="width:280px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $vendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <?php
                  $total = $order->total_price ?? $order->items->sum(fn($i) => $i->price * $i->quantity);
                  $meusItens = $order->items->filter(fn($i) => $i->product && $i->product->user_id === auth()->id());
              ?>
              <tr>
                  <td><?php echo e($order->id); ?></td>
                  <td>
                      <?php echo e($order->user?->name ?? '—'); ?>

                      <br>
                      <small><?php echo e($order->user?->email); ?></small>
                  </td>
                  <td>
                      <?php $__currentLoopData = $meusItens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <div>
                              <?php echo e($item->quantity); ?>x <?php echo e($item->product?->name); ?>

                              <small class="text-muted">R$ <?php echo e(number_format($item->price, 2, ',', '.')); ?></small>
                          </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </td>
                  <td>R$ <?php echo e(number_format($total, 2, ',', '.')); ?></td>
                  <td><?php echo e($order->status ?? '—'); ?></td>
                  <td><?php echo e($order->mp_status ?? '—'); ?></td>
                  <td>
                      <?php if($order->status === 'Concluido' && $order->mp_payment_id): ?>
                          <form method="POST" action="<?php echo e(route('orders.refund', $order)); ?>" class="d-inline">
                              <?php echo csrf_field(); ?>
                              <button class="btn btn-sm btn-warning mb-1"
                                  onclick="return confirm('Confirmar reembolso total deste pedido?')">
                                  Reembolsar total
                              </button>
                          </form>

                          <form method="POST" action="<?php echo e(route('orders.refund', $order)); ?>" class="d-inline-flex align-items-center gap-1 mb-1">
                              <?php echo csrf_field(); ?>
                              <input name="amount" type="number" step="0.01" min="0.01"
                                     class="form-control form-control-sm" style="width: 90px"
                                     placeholder="Valor">
                              <button class="btn btn-sm btn-outline-warning"
                                      onclick="return confirm('Confirmar reembolso parcial?')">
                                  Parcial
                              </button>
                          </form>
                      <?php else: ?>
                          <small class="text-muted">Sem ações</small>
                      <?php endif; ?>
                  </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="7" class="text-center py-3 text-muted">Nenhuma venda encontrada.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <a href="<?php echo e(route('minha.conta')); ?>" class="btn btn-outline-secondary mb-4">
      Voltar
  </a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const labelsDias   = <?php echo json_encode($labelsDias, 15, 512) ?>;
      const serieVendas  = <?php echo json_encode($serieVendas, 15, 512) ?>;
      const labelsStatus = <?php echo json_encode($labelsStatus, 15, 512) ?>;
      const serieStatus  = <?php echo json_encode($serieStatus, 15, 512) ?>;
      const labelsHoras  = <?php echo json_encode($labelsHoras, 15, 512) ?>;
      const serieHora    = <?php echo json_encode($serieHora, 15, 512) ?>;

      // vendas por dia
      if (labelsDias.length && document.getElementById('chartVendasDia')) {
        new Chart(document.getElementById('chartVendasDia'), {
          type: 'line',
          data: {
            labels: labelsDias,
            datasets: [{
              label: 'Vendas (R$)',
              data: serieVendas,
              tension: 0.3
            }]
          },
          options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
              y: {
                beginAtZero: true,
                ticks: {
                  callback: value => 'R$ ' + Number(value).toFixed(2).replace('.', ',')
                }
              }
            }
          }
        });
      }

      // status (agora inclui Retirado)
      if (labelsStatus.length && document.getElementById('chartStatus')) {
        new Chart(document.getElementById('chartStatus'), {
          type: 'doughnut',
          data: {
            labels: labelsStatus,
            datasets: [{
              data: serieStatus
            }]
          },
          options: {
            responsive: true,
            plugins: {
              legend: { position: 'bottom' }
            }
          }
        });
      }

      // por hora
      if (document.getElementById('chartHora')) {
        new Chart(document.getElementById('chartHora'), {
          type: 'bar',
          data: {
            labels: labelsHoras,
            datasets: [{
              label: 'Pedidos',
              data: serieHora
            }]
          },
          options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
          }
        });
      }
    });
  </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\AgroConecta\resources\views/account/mySalesAnalysis.blade.php ENDPATH**/ ?>