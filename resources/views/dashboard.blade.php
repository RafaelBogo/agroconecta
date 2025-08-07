@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container text-center">
    <div class="search-bar">
        <form action="{{ route('dashboard.search') }}" method="GET" class="d-flex">
            <input type="text" name="product" placeholder="Busque por um Produto"
                class="form-control me-2 flex-grow-2" value="{{ request('product') }}">
            <select name="city" class="form-select me-2 flex-grow-1" style="max-width: 150px;">
                <option value="">Cidade</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-success">Buscar</button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .search-bar {
        width: 600px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .search-bar input {
        width: 75%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .search-bar button {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .search-bar button:hover {
        background-color: #45a049;
    }
</style>
@endpush
