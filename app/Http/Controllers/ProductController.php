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
        return view('sell.important');
    }

    public function showCadastroProduto()
    {
        return view('sell.cadastroProduto');
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
                'city' => 'required|string|max:255',
                'photo' => 'required|image|max:2048',
                'stock' => 'required|integer|min:0',
            ]);

            // Upload da foto
            $path = $request->file('photo')->store('products', 'public');

            // Salvar o produto no banco
            Product::create([
                'name' => $validatedData['name'],
                'price' => $validatedData['price'],
                'validity' => $validatedData['validity'],
                'unit' => $validatedData['unit'],
                'contact' => $validatedData['contact'],
                'description' => $validatedData['description'],
                'address' => $validatedData['address'],
                'city' => $validatedData['city'],
                'photo' => $path,
                'user_id' => Auth::id(),
                'stock' => $validatedData['stock'],
            ]);

            return redirect()
                ->back()
                ->with('product_created', true)
                ->with('success_message', 'Produto cadastrado com sucesso!');

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

        $products = $query->get();
        $cities = Product::select('city')->distinct()->pluck('city');

        return view('products.showProducts', compact('products', 'cities'));
    }
   public function showProductDetails($id)
{
    $product = Product::with([
        'user:id,name',          
    ])->findOrFail($id);

    return view('products.details', compact('product'));
}


    public function myProducts()
    {
        $products = Product::where('user_id', auth()->id())->get();

        return view('account.myProducts', compact('products'));
    }

    public function destroy($id)
    {
        $product = Product::where('id', $id)->where('user_id', auth()->id())->first();

        if ($product) {
            $product->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found.']);
    }

    public function edit($id)
    {
        $product = Product::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        return view('account.editProduct', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'city' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'validity' => 'nullable|date',
            'unit' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = Product::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->city = $request->city;
        $product->stock = $request->stock;
        $product->validity = $request->validity;
        $product->unit = $request->unit;
        $product->contact = $request->contact;
        $product->address = $request->address;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('products', 'public');
            $product->photo = $path;
        }

        $product->save();

        return redirect()->back()->with('success', 'Produto atualizado com sucesso!');
    }


}
