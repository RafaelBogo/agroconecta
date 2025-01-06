<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function showImportant()
    {
        return view('sell.important'); // Certifique-se de que a view 'important' existe
    }

    public function showCadastroProduto()
    {
        return view('sell.cadastroProduto'); // Certifique-se de que a view existe
    }

    public function storeCadastroProduto(Request $request)
    {
        try {
            // Validação dos campos
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'validity' => 'required|date',
                'unit' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
                'description' => 'required|string|max:250',
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:255', // Adicione a validação para 'city'
                'photo' => 'required|image|max:2048',
                'stock' => 'required|integer|min:0',
            ]);

            // Upload da foto
            $path = $request->file('photo')->store('products', 'public');

            // Criação do produto no banco
            $product = Product::create([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'validity' => $validatedData['validity'],
                'unit' => $validatedData['unit'],
                'contact' => $validatedData['contact'],
                'description' => $validatedData['description'],
                'address' => $validatedData['address'],
                'city' => $validatedData['city'], // Salvar o campo 'city'
                'photo' => $path,
                'user_id' => Auth::id(),
                'stock' => $validatedData['stock'],
            ]);


            Log::info('Produto cadastrado com sucesso:', ['product_id' => $product->id]);

            return redirect()->route('dashboard')->with('success', 'Produto cadastrado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao cadastrar produto:', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return redirect()->back()->withErrors(['error' => 'Erro ao salvar o produto. Por favor, tente novamente.']);
        }
    }

    public function showProducts(Request $request)
    {
        $query = Product::query();

        if ($request->filled('product')) {
            $query->where('name', 'like', '%' . $request->product . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $products = $query->get();
        $cities = Product::select('city')->distinct()->pluck('city');

        return view('products.showProducts', compact('products', 'cities'));
    }


    public function search(Request $request)
    {
        $query = Product::query();

        // Filtros
        if ($request->filled('product')) {
            $query->where('name', 'like', '%' . $request->product . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $products = $query->get(); // Produtos filtrados
        $cities = Product::select('city')->distinct()->pluck('city'); // Lista de cidades

        return view('products.showProducts', compact('products', 'cities'));
    }




}
