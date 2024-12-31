<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //step-1
    public function showImportant()
    {
        return view('sell.important');
    }

    public function showStep1()
    {
        return view('sell.step1'); // Certifique-se de que 'step1.blade.php' existe em resources/views/sell
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

    //step-2
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

    //step-3
    public function showStep3()
    {
        return view('sell.step3'); // Certifique-se de que o arquivo 'step3.blade.php' está na pasta correta
    }

    public function storeStep3(Request $request)
{
    $validatedData = $request->validate([
        'product_image' => 'required|image|max:2048', // Validação para aceitar apenas imagens de até 2MB
    ]);

    // Salve a imagem no storage
    $path = $request->file('product_image')->store('products', 'public');

    // Opcional: Salve o caminho da imagem no banco de dados
    // Product::create([...]);

    return redirect()->route('sell.complete')->with('success', 'Imagem carregada com sucesso!');
}


    public function showComplete()
    {
        return view('sell.complete');
    }
}
