<?php

namespace App\Http\Controllers;


use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    public function viewCart()
{
    // Obtém os itens do carrinho armazenados na sessão
    $cartItems = session('cart', []);

    // Retorna a view com os itens do carrinho
    return view('cart.view', compact('cartItems'));
}


    public function addToCart(Request $request)
    {
        Log::info('addToCart chamado', $request->all()); // Log para verificar

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'photo' => $product->photo,
                'quantity' => $request->quantity,
            ];
        }

        session()->put('cart', $cart);

        Log::info('Carrinho atualizado', session('cart')); // Log do estado do carrinho

        return response()->json(['message' => 'Produto adicionado ao carrinho com sucesso!']);
    }


    public function deleteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
        ]);

        // Recupera o carrinho da sessão
        $cart = session()->get('cart', []);

        // Remove o item especificado
        if (isset($cart[$request->item_id])) {
            unset($cart[$request->item_id]);

            // Atualiza o carrinho na sessão
            session()->put('cart', $cart);

            return response()->json(['success' => 'Item removido do carrinho com sucesso!']);
        }

        return response()->json(['error' => 'Item não encontrado no carrinho.'], 404);
    }


}
