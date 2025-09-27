@extends('layouts.app')

@section('title', 'Editar Produto')
@section('boxed', true)

@section('content')
@php
  $cities = [
    'Chapecó','Xanxerê','Xaxim','Pinhalzinho','Palmitos','Maravilha','Modelo','Saudades','Águas de Chapecó',
    'Nova Erechim','Nova Itaberaba','Coronel Freitas','Quilombo','Abelardo Luz','Coronel Martins','Galvão',
    'São Lourenço do Oeste','Campo Erê','Saltinho','São Domingos','Ipuaçu','Entre Rios','Jupiá',
    'Itapiranga','Iporã do Oeste','Mondaí','Riqueza','Descanso','Tunápolis','Belmonte','Paraíso',
    'São Miguel do Oeste','Guaraciaba','Anchieta','Dionísio Cerqueira','Barra Bonita'
  ];
@endphp

@php
  $units = [
    'un'      => 'Unidade (un)',
    'dz'      => 'Dúzia (dz)',
    'kg'      => 'Quilo (kg)',
    'g'       => 'Grama (g)',
    't'       => 'Tonelada (t)',
    'l'       => 'Litro (L)',
    'ml'      => 'Mililitro (mL)',
    'saca60'  => 'Saca 60 kg',
    'caixa'   => 'Caixa',
    'maco'    => 'Maço',
    'bandeja' => 'Bandeja',
  ];
  $currentUnit = old('unit', $product->unit);
  $isCustom    = !array_key_exists($currentUnit, $units);
@endphp



    <div class="left-section">
        <h4>Editar Produto</h4>
        <p>Aqui você altera informações sobre o seu produto</p>
        <img src="{{ asset('storage/' . $product->photo) }}" alt="{{ $product->name }}" class="product-image">
    </div>
    <div class="right-section">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')



           
    <div class="mb-3">
        <label for="photo" class="form-label">Nova foto</label>
        <input id="photo" type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp,image/avif">
        <div class="form-text">JPG, PNG, WEBP ou AVIF — até 3 MB.</div>
    </div>


    <div class="mb-3">
        <label for="name" class="form-label">Nome do Produto</label>
        <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
    </div>


    <div class="mb-3">
        <label for="description" class="form-label">Descrição do Produto</label>
        <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
    </div>


    <div class="mb-3">
        <label for="price" class="form-label">Valor (R$)</label>
        <input id="price" type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}" required>
</div>


    <div class="mb-3">
        <label for="city" class="form-label">Cidade (Oeste Catarinense)</label>
        <select id="city" name="city" class="form-select" required>
                <option value="" disabled>Selecione sua cidade</option>
            @foreach ($cities as $c)
                <option value="{{ $c }}" @selected(old('city', $product->city) === $c)>{{ $c }}</option>
            @endforeach
            @php $currentCity = old('city', $product->city); @endphp
            @if ($currentCity && !in_array($currentCity, $cities))
                <option value="{{ $currentCity }}" selected>{{ $currentCity }} (outra)</option>
             @endif
        </select>
    </div>


    <div class="mb-3">
        <label for="stock" class="form-label">Estoque Disponível</label>
        <input id="stock" type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
    </div>


    <div class="mb-3">
        <label for="validity" class="form-label">Validade do Produto</label>
        <input id="validity" type="date" name="validity" class="form-control" value="{{ old('validity', $product->validity) }}">
    </div>

   <div class="mb-3">
        <label for="unit" class="form-label">Unidade de Medida</label>
        <select id="unit" name="unit" class="form-select" required>
        <option value="" disabled {{ $currentUnit ? '' : 'selected' }}>Selecione…</option>
            @foreach ($units as $val => $label)
        <option value="{{ $val }}" @selected($currentUnit === $val)>{{ $label }}</option>
            @endforeach
        <option value="custom" @selected($isCustom)>Outra…</option>
        </select>

        <input type="text" id="unit_custom" name="unit_custom" class="form-control mt-2 {{ $isCustom ? '' : 'd-none' }}" placeholder="Digite a unidade (ex.: 'arroba 15 kg')" value="{{ old('unit_custom', $isCustom ? $currentUnit : '') }}">
    </div>



    <div class="mb-3">
        <label for="contact" class="form-label">Telefone para Contato</label>
        <input id="contact" type="text" name="contact" class="form-control" value="{{ old('contact', $product->contact) }}">
    </div>


    <div class="mb-3">
        <label for="address" class="form-label">Endereço Completo</label>
        <textarea id="address" name="address" class="form-control" rows="3">{{ old('address', $product->address) }}</textarea>
    </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-success">Salvar</button>
                <a href="{{ route('account.myProducts') }}" class="btn btn-secondary">Voltar</a>
            </div>
        </form>
    </div>

    <!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sucesso!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    O produto foi atualizado com sucesso!
                </div>
                <div class="modal-footer">
                    <a href="{{ route('account.myProducts') }}" class="btn btn-success">OK</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .left-section {
        flex: 1;
        padding-right: 20px;
    }

    .right-section {
        flex: 2;
        display: flex;
        flex-direction: column;
    }

    .form-control {
        border-radius: 10px;
        margin-bottom: 15px;
        padding: 15px;
    }

    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .product-image {
        width: 100%;
        max-width: 250px;
        height: auto;
        border-radius: 10px;
        margin: 0 auto;
        position: relative;
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-success, .btn-secondary {
        padding: 10px 30px;
    }

    .btn-secondary {
        background-color: black;
        border: none;
    }
</style>
@endpush

@push('scripts')
<script>
    @if (session('success'))
        window.onload = function() {
            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        };
    @endif
</script>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function(){
    const sel  = document.getElementById('unit');
    const cust = document.getElementById('unit_custom');

    function toggleCustom(){
      if (sel.value === 'custom') cust.classList.remove('d-none');
      else { cust.classList.add('d-none'); if (!cust.dataset.keep) cust.value = ''; }
    }
    sel.addEventListener('change', toggleCustom);
    toggleCustom();
  });
</script>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('successModal');
    if (!el) return;

    //O modal não fica dentro de containers com overflow/transform
    document.body.appendChild(el);

    // faz a limpeza total quando fechar (remove o backdrop e libera o scroll)
    el.addEventListener('hidden.bs.modal', () => {
      document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('padding-right');
    });
  });
</script>
@endpush

@endpush

@endpush
