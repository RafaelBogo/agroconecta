{{-- resources/views/account/mySalesAnalysis.blade.php --}}
@extends('layouts.app')

@section('title', 'Análise de Vendas')
@section('boxed', true)

@section('content')
  <h1 class="mb-4">Minhas Vendas — Análise</h1>

  @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
      <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  @php
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
  @endphp

  {{-- cards --}}
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Total vendido</div>
          <div class="h4 mb-0">{{ $fmtBRL($totalVendas) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Pedidos</div>
          <div class="h4 mb-0">{{ $totalPedidos }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-body">
          <div class="text-muted small mb-1">Ticket médio</div>
          <div class="h4 mb-0">{{ $fmtBRL($ticketMedio) }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- gráficos --}}
  <div class="row g-4 mb-4">
    <div class="col-lg-7">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white fw-semibold">Vendas por dia</div>
        <div class="card-body">
          @if ($labelsDias->isEmpty())
            <p class="text-muted mb-0">Ainda não há vendas para montar o gráfico.</p>
          @else
            <canvas id="chartVendasDia" style="height: 240px;"></canvas>
          @endif
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card shadow-sm border-0 h-100">
        <div class="card-header bg-white fw-semibold">Status dos pedidos</div>
        <div class="card-body">
          @if ($labelsStatus->isEmpty())
            <p class="text-muted mb-0">Sem pedidos para exibir.</p>
          @else
            <canvas id="chartStatus" style="height: 240px;"></canvas>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- pedidos por horário --}}
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white fw-semibold">Pedidos por horário</div>
    <div class="card-body">
      <canvas id="chartHora" style="height: 200px;"></canvas>
    </div>
  </div>

  {{-- pedidos listados --}}
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
            @forelse($vendas as $order)
              @php
                  $total = $order->total_price ?? $order->items->sum(fn($i) => $i->price * $i->quantity);
                  $meusItens = $order->items->filter(fn($i) => $i->product && $i->product->user_id === auth()->id());
              @endphp
              <tr>
                  <td>{{ $order->id }}</td>
                  <td>
                      {{ $order->user?->name ?? '—' }}
                      <br>
                      <small>{{ $order->user?->email }}</small>
                  </td>
                  <td>
                      @foreach($meusItens as $item)
                          <div>
                              {{ $item->quantity }}x {{ $item->product?->name }}
                              <small class="text-muted">R$ {{ number_format($item->price, 2, ',', '.') }}</small>
                          </div>
                      @endforeach
                  </td>
                  <td>R$ {{ number_format($total, 2, ',', '.') }}</td>
                  <td>{{ $order->status ?? '—' }}</td>
                  <td>{{ $order->mp_status ?? '—' }}</td>
                  <td>
                      @if($order->status === 'Concluido' && $order->mp_payment_id)
                          <form method="POST" action="{{ route('orders.refund', $order) }}" class="d-inline">
                              @csrf
                              <button class="btn btn-sm btn-warning mb-1"
                                  onclick="return confirm('Confirmar reembolso total deste pedido?')">
                                  Reembolsar total
                              </button>
                          </form>

                          <form method="POST" action="{{ route('orders.refund', $order) }}" class="d-inline-flex align-items-center gap-1 mb-1">
                              @csrf
                              <input name="amount" type="number" step="0.01" min="0.01"
                                     class="form-control form-control-sm" style="width: 90px"
                                     placeholder="Valor">
                              <button class="btn btn-sm btn-outline-warning"
                                      onclick="return confirm('Confirmar reembolso parcial?')">
                                  Parcial
                              </button>
                          </form>
                      @else
                          <small class="text-muted">Sem ações</small>
                      @endif
                  </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-3 text-muted">Nenhuma venda encontrada.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <a href="{{ route('minha.conta') }}" class="btn btn-outline-secondary mb-4">
      Voltar
  </a>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const labelsDias   = @json($labelsDias);
      const serieVendas  = @json($serieVendas);
      const labelsStatus = @json($labelsStatus);
      const serieStatus  = @json($serieStatus);
      const labelsHoras  = @json($labelsHoras);
      const serieHora    = @json($serieHora);

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
@endpush
