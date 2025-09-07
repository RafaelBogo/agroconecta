@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-hero d-flex align-items-center justify-content-center">
  <div class="search-card shadow-sm">
    <div class="text-center mb-3">
      <div class="display-6 mb-2" style="line-height:1"><i class="bi bi-search"></i></div>
      <h2 class="mb-1">Encontre produtos fresquinhos</h2>
      <p class="text-muted mb-0">Busque por nome e filtre por cidade</p>
    </div>

    <form action="{{ route('dashboard.search') }}" method="GET" class="row g-2">
      <div class="col-12 col-md-7">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-bag"></i></span>
          <input
            type="text"
            name="product"
            class="form-control"
            placeholder="Ex.: tomate, mel, queijo…"
            value="{{ request('product') }}"
            aria-label="Buscar por produto"
          >
          <button type="button" id="clearProduct" class="btn btn-outline-secondary d-none">
            <i class="bi bi-x-lg"></i>
          </button>
        </div>
      </div>

      <div class="col-12 col-md-3">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
          <select name="city" class="form-select" aria-label="Filtrar por cidade">
            <option value="">Todas as cidades</option>
            @foreach($cities as $city)
              <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                {{ $city }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="col-12 col-md-2 d-grid">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-search"></i> Buscar
        </button>
      </div>
    </form>

    @if(request()->filled('product') || request()->filled('city'))
      <div class="text-center mt-3">
        <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none">
          Limpar filtros
        </a>
      </div>
    @endif
  </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
  .dashboard-hero{
    min-height: calc(100vh - 120px);
    padding: 24px 12px;
  }
  .search-card{
    width: 100%;
    max-width: 900px;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(5px);
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
  }
  @media (max-width: 576px){
    .search-card{ padding: 20px; }
  }
</style>
@endpush

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('input[name="product"]');
    const clearBtn = document.getElementById('clearProduct');

    const toggleClear = () => clearBtn.classList.toggle('d-none', !input.value.trim());
    input?.addEventListener('input', toggleClear);
    toggleClear();

    clearBtn?.addEventListener('click', () => {
      input.value = '';
      input.focus();
      toggleClear();
    });
  });
</script>
@endpush
