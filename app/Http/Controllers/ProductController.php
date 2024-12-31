<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function showStep1()
    {
        return view('sell.step1');
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'validity' => 'required|date',
            'unit' => 'required',
            'contact' => 'required|email',
        ]);

        $product = new Product();
        $product->fill($request->all());
        $product->save();

        return redirect()->route('sell.step2')->with('product_id', $product->id);
    }

    public function showStep2()
    {
        return view('sell.step2'); // Certifique-se de que o arquivo step2.blade.php exista
    }

    public function storeStep2(Request $request){
        // Validação dos dados enviados
        $request->validate([
            'description' => 'required|max:250',
            'pickup_address' => 'required|max:250',
            'save_address' => 'nullable|boolean',
        ]);

        // Salvando os dados temporários na sessão ou banco
        session()->put('step2', $request->only('description', 'pickup_address', 'save_address'));

        // Redirecionando para o Step 3
        return redirect()->route('products.step3');
    }

    public function storeStep3(Request $request)
    {
        $request->validate([
            'photo' => 'required|image',
        ]);

        $product = Product::find(session('product_id'));
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('products', 'public');
            $product->photo = $path;
        }
        $product->save();

        return redirect()->route('sell.complete');
    }

    public function showComplete()
    {
        return view('sell.complete');
    }
}
