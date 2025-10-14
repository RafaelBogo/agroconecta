@extends('layouts.app')

@section('title', 'Meus Dados')
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


    <h2>Meus Dados</h2>

    

    <form action="{{ route('user.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Seu Nome Completo</label>
                    <input type="text" id="name" name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="{{ $user->email }}" readonly>
                </div>

                <div class="form-group">
                    <label for="phone">Telefone</label>
                    <input type="text" id="phone" name="phone" class="form-control"
                           placeholder="(00) 00000-0000"
                           value="{{ old('phone', $user->phone) }}" required>
                </div>

                <div class="form-group">
                    <label for="city">Cidade</label>
                    <select id="city" name="city" class="form-control" required>
                        <option value="" disabled {{ old('city', $user->city) ? '' : 'selected' }}>
                            Selecione sua cidade
                        </option>
                        @foreach ($cities as $city)
                            <option value="{{ $city }}" {{ old('city', $user->city) === $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Seu Endereço Completo</label>
                    <textarea id="address" name="address" class="form-control" rows="8"
                              placeholder="Cidade, comunidade/bairro, rua, ponto de referência, cor da casa..." required>{{ old('address', $user->address) }}</textarea>
                </div>
            </div>
        </div>

        <div class="btn-container">
            <a href="{{ route('minha.conta') }}" class="btn btn-secondary">Voltar</a>
            <button type="submit" class="btn btn-success">Salvar</button>
        </div>
    </form>

    <!-- Modal de Sucesso -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Sucesso!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    Seus dados foram atualizados com sucesso!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    h2 {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #ccc;
        padding: 15px;
        font-size: 16px;
        width: 100%;
    }

    .form-control:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
    }

    .btn-container {
        display: flex;
        justify-content: space-between;
    }

    .btn-success {
        padding: 10px 30px;
    }

    .btn-secondary {
        padding: 10px 30px;
        background-color: black;
        border: none;
    }

    /* sobe o modal pra cima de qualquer overlay da página*/
    .modal { z-index: 2000 !important; }
    .modal-backdrop { z-index: 1990 !important; }
    .modal-backdrop.show { pointer-events: none; }



</style>
@endpush

@push('scripts')
@if (session('success'))
<script>
  window.onload = function () {
    const el = document.getElementById('successModal');
    document.body.appendChild(el); 
    const successModal = new bootstrap.Modal(el);
    successModal.show();
  };
</script>
@endif

@endpush
