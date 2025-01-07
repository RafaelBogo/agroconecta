<?php

namespace App\Http\Controllers;

use App\Mail\PedidoFinalizado;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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
        Log::info('addToCart chamado', $request->all());

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

        Log::info('Carrinho atualizado', session('cart'));

        return response()->json(['message' => 'Produto adicionado ao carrinho com sucesso!']);
    }

    public function deleteItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->item_id])) {
            unset($cart[$request->item_id]);

            session()->put('cart', $cart);

            $total = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            return response()->json([
                'success' => 'Item removido do carrinho com sucesso!',
                'total' => $total,
            ]);
        }

        return response()->json(['error' => 'Item não encontrado no carrinho.'], 404);
    }

    public function getCartSummary()
    {
        $cart = session()->get('cart', []);

        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        return response()->json(['total' => $total]);
    }

    public function finalizarPedido(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return response()->json(['error' => 'O carrinho está vazio.'], 400);
        }

        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $orderDetails = [
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'items' => array_map(function ($item) {
                return [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ];
            }, $cart),
            'total' => $total,
        ];

        try {
            // Envia o e-mail usando o Mailable
            Mail::to($orderDetails['user_email'])->send(new PedidoFinalizado($orderDetails));

            // Limpa o carrinho
            session()->forget('cart');

            return response()->json(['success' => 'Pedido finalizado com sucesso!']);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar o e-mail: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar o pedido. Tente novamente mais tarde.'], 500);
        }
    }
}
