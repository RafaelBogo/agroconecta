@extends('layouts.app')

@section('title', 'Cadastro de Produto')
@section('boxed', true)
@section('back', content: route('account.myProducts'))

@section('content')
@php
    $cities = [
        'Abelardo Luz','Águas de Chapecó','Anchieta','Barra Bonita','Belmonte','Bom Jesus',
        'Campo Erê','Chapecó','Coronel Freitas','Coronel Martins','Descanso','Dionísio Cerqueira',
        'Entre Rios','Galvão','Guaraciaba','Ipuaçu','Iporã do Oeste','Itapiranga','Jupiá','Maravilha',
        'Modelo','Mondaí','Nova Erechim','Nova Itaberaba','Palmitos','Paraíso','Pinhalzinho',
        'Quilombo','Riqueza','Saltinho','São Domingos','São Lourenço do Oeste','São Miguel do Oeste',
        'Saudades','Tunápolis','Xanxerê','Xaxim',
    ];
@endphp

<div class="mb-5 text-center">
    <h2 class="fw-semibold mb-1"><i class="bi bi-box-seam text-success me-2"></i>Cadastro de Produto</h2>
    <p class="text-muted">Preencha as informações do produto para colocá-lo à venda no AgroConecta.</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="row g-5">
    @csrf

    {{-- Coluna esquerda --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h5 class="card-title mb-3">
                    <i class="bi bi-info-circle text-success me-2"></i>Informações do Produto
                </h5>

                <div class="mb-3">
                    <label for="name" class="form-label">Nome do Produto</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea id="description" name="description" class="form-control" rows="4"
                        placeholder="Descreva o produto, tipo, origem, diferenciais..." required>{{ old('description') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Preço (R$)</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01"
                            value="{{ old('price') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label">Estoque Disponível</label>
                        <input type="number" id="stock" name="stock" class="form-control"
                            value="{{ old('stock') }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="unit" class="form-label">Unidade de Medida</label>
                        <select id="unit" name="unit" class="form-select" required>
                            <option value="" disabled {{ old('unit') ? '' : 'selected' }}>Selecione...</option>
                            <option value="un" @selected(old('unit') === 'un')>Unidade (un)</option>
                            <option value="dz" @selected(old('unit') === 'dz')>Dúzia (dz)</option>
                            <option value="kg" @selected(old('unit') === 'kg')>Quilo (kg)</option>
                            <option value="g" @selected(old('unit') === 'g')>Grama (g)</option>
                            <option value="l" @selected(old('unit') === 'l')>Litro (L)</option>
                            <option value="bandeja" @selected(old('unit') === 'bandeja')>Bandeja</option>
                            <option value="custom" @selected(old('unit') === 'custom')>Outra…</option>
                        </select>

                        <input type="text" id="unit_custom" name="unit_custom"
                            class="form-control mt-2 {{ old('unit') === 'custom' ? '' : 'd-none' }}"
                            placeholder="Digite a unidade (ex.: 'arroba 15 kg')" value="{{ old('unit_custom') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="validity" class="form-label">Validade</label>
                        <input type="date" id="validity" name="validity" class="form-control"
                            value="{{ old('validity') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Coluna direita --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body p-4 d-flex flex-column">
                <h5 class="card-title mb-3">
                    <i class="bi bi-geo-alt text-success me-2"></i>Localização e Contato
                </h5>

                <div class="mb-3">
                    <label for="city" class="form-label">Cidade</label>
                    <select id="city" name="city" class="form-select @error('city') is-invalid @enderror" required>
                        <option value="" disabled {{ old('city') ? '' : 'selected' }}>Selecione sua cidade</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c }}" @selected(old('city') === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                    @error('city') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Endereço completo</label>
                    <input list="saved_addresses_list" id="address" name="address" class="form-control"
                        placeholder="Insira ou selecione um endereço" value="{{ old('address') }}" required>
                    <datalist id="saved_addresses_list">
                        @if (!empty(auth()->user()->addresses))
                            @foreach (auth()->user()->addresses as $addr)
                                <option value="{{ $addr }}">{{ $addr }}</option>
                            @endforeach
                        @endif
                    </datalist>
                </div>

                <div class="mb-3">
                    <label for="contact" class="form-label">Telefone para Contato</label>
                    <input type="text" id="contact" name="contact" class="form-control"
                        placeholder="(49) 99999-9999" value="{{ old('contact') }}" required>
                </div>

                <div class="mb-4">
                    <label for="photo" class="form-label">Foto do Produto</label>
                    <input type="file" id="photo" name="photo" class="form-control"
                        accept="image/jpeg,image/png,image/webp,image/avif" required>
                    <div class="form-text">Formatos aceitos: JPG, PNG, WEBP, AVIF — até 3 MB.</div>
                </div>

                <div class="mt-auto d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-check-circle me-1"></i> Cadastrar Produto
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Modal de Dicas --}}
<div class="modal fade" id="sellTipsModal" tabindex="-1" aria-labelledby="sellTipsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="sellTipsLabel">
                    <i class="bi bi-lightbulb-fill text-success me-2"></i>Dicas para vender melhor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Use boas fotos:</strong> claras, com fundo neutro e bem iluminadas.</li>
                    <li class="list-group-item"><strong>Descreva bem:</strong> tipo, quantidade, origem e diferenciais.</li>
                    <li class="list-group-item"><strong>Defina preço justo:</strong> pesquise o mercado e seus custos.</li>
                    <li class="list-group-item"><strong>Localização clara:</strong> informe cidade e ponto de referência.</li>
                    <li class="list-group-item"><strong>Atualize informações:</strong> validade, estoque e contato.</li>
                    <li class="list-group-item"><strong>Responda rápido:</strong> agilidade aumenta as vendas.</li>
                    <li class="list-group-item"><strong>Embale bem:</strong> se for enviar, proteja o produto.</li>
                </ul>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK, entendi</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Sucesso --}}
<div class="modal fade" id="sellSuccessModal" tabindex="-1" aria-labelledby="sellSuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="sellSuccessLabel">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>Sucesso!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                {{ session('success_message') ?? 'Produto cadastrado com sucesso!' }}
            </div>
            <div class="modal-footer border-0">
                <a href="{{ route('account.myProducts') }}" class="btn btn-success">OK</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .content-box {
        padding: 60px !important; /* mais espaçamento interno */
        margin-top: 40px !important;
        margin-bottom: 60px !important;
    }

    .card {
        border-radius: 16px;
    }

    .form-label {
        font-weight: 500;
    }

    .card-body {
        min-height: 540px;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/sell.cadastroProduto.js') }}" defer></script>
@endpush
