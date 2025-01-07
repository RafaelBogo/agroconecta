<?php

namespace App\Http\Controllers;

use App\Mail\PedidoFinalizado;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Order;

class CartController extends Controller
{
    public function viewCart()
    {
        $cartItems = session('cart', []);
        Log::info('Conteúdo do carrinho ao exibir:', $cartItems);

        return view('cart.view', compact('cartItems'));
    }
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Obtém o carrinho da sessão
        $cart = session()->get('cart', []);

        // Adiciona ou atualiza o produto no carrinho
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $product->id) {
                $item['quantity'] += $request->quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'id' => $product->id, // Garante que o ID do produto é salvo como valor
                'name' => $product->name,
                'price' => $product->price,
                'photo' => $product->photo,
                'quantity' => $request->quantity,
            ];
        }

        // Atualiza a sessão do carrinho
        session()->put('cart', $cart);

        // Log para verificar o carrinho atualizado
        Log::info('Carrinho atualizado após adicionar produto:', session('cart'));

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

        Log::info('Conteúdo do carrinho ao finalizar pedido:', $cart);

        if (empty($cart)) {
            return response()->json(['error' => 'O carrinho está vazio.'], 400);
        }

        foreach ($cart as $item) {
            if (!isset($item['id'])) {
                Log::error('ID do produto ausente no carrinho:', $item);
                return response()->json(['error' => 'Erro: Produto no carrinho sem ID.'], 400);
            }

            // Criação do pedido na tabela 'orders'
            Order::create([
                'user_id' => Auth::id(),
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'total_price' => $item['price'] * $item['quantity'],
                'status' => 'Processando',
            ]);
        }

        $total = array_reduce($cart, function ($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $orderDetails = [
            'user_name' => Auth::user()->name,
            'user_email' => Auth::user()->email,
            'items' => array_map(function ($item) {
                $product = Product::find($item['id']);
                return [
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'seller_address' => $product->address ?? 'Endereço não informado',
                ];
            }, $cart),
            'total' => $total,
        ];

        try {
            // Envia o e-mail com os detalhes do pedido
            Mail::to($orderDetails['user_email'])->send(new PedidoFinalizado($orderDetails));

            // Limpa o carrinho após finalizar o pedido
            session()->forget('cart');

            return response()->json(['success' => 'Pedido finalizado com sucesso!']);
        } catch (\Exception $e) {
            Log::error('Erro ao finalizar pedido: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar o pedido.'], 500);
        }
    }



}
