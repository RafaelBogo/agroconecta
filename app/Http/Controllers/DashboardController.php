<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Lista de cidades disponíveis para o filtro
        $cities = Product::select('city')->distinct()->pluck('city');
        // Inicialmente exibe todos os produtos
        $products = Product::all();
        return view('dashboard', compact('cities', 'products'));
    }

    public function search(Request $request)
    {
        $query = Product::query();

        // Filtra pela cidade, se informada
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filtra pelo nome do produto, se informado
        if ($request->filled('product')) {
            $query->where('name', 'like', '%' . $request->product . '%');
        }

        // Obtém os resultados filtrados
        $products = $query->get();

        // Atualiza a lista de cidades para o dropdown
        $cities = Product::select('city')->distinct()->pluck('city');

        return view('dashboard', compact('cities', 'products'));
    }

    public function minhaConta()
    {
        return view('minhaConta');
    }
}

