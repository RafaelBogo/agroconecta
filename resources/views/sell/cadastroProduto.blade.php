@extends('layouts.app')

@section('title', 'Cadastro de Produto')
@section('boxed', true)

@section('content')
  <h2 class="text-center mb-4">Cadastro de Produto</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
      </ul>
    </div>
  @endif

  @php
      $cities = [
        'Chapecó','Xanxerê','Xaxim','Pinhalzinho','Palmitos','Maravilha','Modelo','Saudades','Águas de Chapecó',
        'Nova Erechim','Nova Itaberaba','Coronel Freitas','Quilombo','Abelardo Luz','Coronel Martins','Galvão',
        'São Lourenço do Oeste','Campo Erê','Saltinho','São Domingos','Ipuaçu','Entre Rios','Jupiá',
        'Itapiranga','Iporã do Oeste','Mondaí','Riqueza','Descanso','Tunápolis','Belmonte','Paraíso',
        'São Miguel do Oeste','Guaraciaba','Anchieta','Dionísio Cerqueira','Barra Bonita'
      ];
    @endphp


  <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
    @csrf

    <div class="col-12">
      <label for="name" class="form-label">Nome do Produto</label>
      <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="col-12">
      <label for="description" class="form-label">Descrição</label>
      <textarea id="description" name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
    </div>

    <div class="col-12 col-md-4">
      <label for="price" class="form-label">Preço</label>
      <input type="number" id="price" name="price" class="form-control" step="0.01" value="{{ old('price') }}" required>
    </div>

    <div class="col-12 col-md-4">
      <label for="city" class="form-label">Cidade (Oeste Catarinense)</label>
        <select id="city" name="city" class="form-select @error('city') is-invalid @enderror" required>
          <option value="" disabled {{ old('city') ? '' : 'selected' }}>Selecione sua cidade</option>
             @foreach ($cities as $c)
            <option value="{{ $c }}" {{ old('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
        </select>
            @error('city') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>



    <div class="col-12">
      <label for="address" class="form-label">Endereço</label>
      <input list="saved_addresses_list" id="address" name="address" class="form-control" placeholder="Insira ou selecione um endereço" value="{{ old('address') }}" required>
      <datalist id="saved_addresses_list">
        @if (!empty(auth()->user()->addresses))
          @foreach (auth()->user()->addresses as $addr)
            <option value="{{ $addr }}">{{ $addr }}</option>
          @endforeach
        @endif
      </datalist>
    </div>

    <div class="col-12 col-md-4">
      <label for="stock" class="form-label">Estoque</label>
      <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock') }}" required>
    </div>

    <div class="col-12 col-md-4">
      <label for="unit" class="form-label">Unidade de Medida</label>
      <select id="unit" name="unit" class="form-select" required>
        <option value="" disabled {{ old('unit') ? '' : 'selected' }}>Selecione…</option>

        <option value="un"     @selected(old('unit')==='un')>Unidade (un)</option>
        <option value="dz"     @selected(old('unit')==='dz')>Dúzia (dz)</option>
        <option value="kg"     @selected(old('unit')==='kg')>Quilo (kg)</option>
        <option value="g"      @selected(old('unit')==='g')>Grama (g)</option>
        <option value="l"      @selected(old('unit')==='l')>Litro (L)</option>
        <option value="bandeja"@selected(old('unit')==='bandeja')>Bandeja</option>
      </select>

      <input
        type="text"
        id="unit_custom"
        name="unit_custom"
        class="form-control mt-2 {{ old('unit')==='custom' ? '' : 'd-none' }}"
        placeholder="Digite a unidade (ex.: 'arroba 15 kg')"
        value="{{ old('unit_custom') }}"
      >
    </div>

    <div class="col-12 col-md-4">
      <label for="validity" class="form-label">Validade</label>
      <input type="date" id="validity" name="validity" class="form-control" value="{{ old('validity') }}" required>
    </div>

    <div class="col-12 col-md-6">
      <label for="contact" class="form-label">Telefone para Contato</label>
      <input type="text" id="contact" name="contact" class="form-control" value="{{ old('contact') }}" required>
    </div>

    <div class="col-12 col-md-6">
      <label for="photo" class="form-label">Foto do Produto</label>
      <input type="file" id="photo" name="photo" class="form-control" required>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-success w-100">Cadastrar Produto</button>
    </div>
  </form>

  {{-- Modal de Dicas --}}
  <div class="modal fade" id="sellTipsModal" tabindex="-1" aria-labelledby="sellTipsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0">
        <div class="modal-header">
          <h5 class="modal-title" id="sellTipsLabel">Dicas rápidas para vender melhor</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Use boas fotos:</strong> claras, com fundo neutro; mostre detalhes.</li>
            <li class="list-group-item"><strong>Descreva bem:</strong> tipo, quantidade, origem, diferenciais (ex.: orgânico).</li>
            <li class="list-group-item"><strong>Preço justo:</strong> pesquise o mercado e considere seus custos.</li>
            <li class="list-group-item"><strong>Localização clara:</strong> cidade/bairro e referência ajudam o cliente.</li>
            <li class="list-group-item"><strong>Qualidade & validade:</strong> mantenha estoque e informações atualizadas.</li>
            <li class="list-group-item"><strong>Responda rápido:</strong> agilidade nas dúvidas converte mais.</li>
            <li class="list-group-item"><strong>Embale bem:</strong> se for enviar, proteja o produto no transporte.</li>
          </ul>
        </div>
        <div class="modal-footer">
          <button id="tips-ok-btn" type="button" class="btn btn-success" data-bs-dismiss="modal">OK, entendi</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal de Sucesso --}}
  <div class="modal fade" id="sellSuccessModal" tabindex="-1" aria-labelledby="sellSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content border-0">
        <div class="modal-header">
          <h5 class="modal-title" id="sellSuccessLabel">Tudo certo!</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <div class="modal-body">
          {{ session('success_message') ?? 'Produto cadastrado com sucesso!' }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/sell.cadastroProduto.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/sell.cadastroProduto.js') }}" defer></script>
@endpush