<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $cities = Product::select('city')->distinct()->pluck('city');
        $products = Product::all(); // Inicialmente exibe todos os produtos
        return view('dashboard', compact('cities', 'products'));
    }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('product')) {
            $query->where('name', 'like', '%' . $request->product . '%');
        }

        $products = $query->get();
        $cities = Product::select('city')->distinct()->pluck('city');

        return view('dashboard', compact('cities', 'products'));
    }
}
