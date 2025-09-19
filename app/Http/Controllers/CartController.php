<?php

namespace App\Http\Controllers;

use App\Mail\PedidoFinalizado;
use App\Mail\PedidoFinalizadoVendedor;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;


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

        // Verifica se a quantidade solicitada excede o estoque disponível
        $cart = session()->get('cart', []);

        // Soma a quantidade no carrinho com a quantidade solicitada
        $currentCartQuantity = 0;
        foreach ($cart as $item) {
            if ($item['id'] == $product->id) {
                $currentCartQuantity = $item['quantity'];
                break;
            }
        }

        if ($currentCartQuantity + $request->quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'error' => "Estoque insuficiente! Apenas {$product->stock} unidade(s) disponível(is)."
            ], 400);
        }

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
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'photo' => $product->photo,
                'quantity' => $request->quantity,
            ];
        }

        // Atualiza a sessão do carrinho
        session()->put('cart', $cart);

        Log::info('Carrinho atualizado após adicionar produto:', session('cart'));

        return response()->json([
            'success' => true,
            'message' => 'Produto adicionado ao carrinho com sucesso!'
        ]);
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

        // Inicia uma transação para garantir consistência no banco de dados
        DB::beginTransaction();

        try {
            foreach ($cart as $item) {
                if (!isset($item['id'])) {
                    Log::error('ID do produto ausente no carrinho:', $item);
                    return response()->json(['error' => 'Erro: Produto no carrinho sem ID.'], 400);
                }

                // Busca o produto no banco de dados
                $product = Product::findOrFail($item['id']);

                // Verifica se há estoque suficiente
                if ($product->stock < $item['quantity']) {
                    return response()->json([
                        'error' => "Estoque insuficiente para o produto {$product->name}. Estoque disponível: {$product->stock}."
                    ], 400);
                }

                // Reduz o estoque do produto
                $product->stock -= $item['quantity'];
                $product->save();

                // Cria o pedido na tabela orders
                Order::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item['id'],
                    'seller_id'   => $product->user_id,
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'status' => 'Processando',
                ]);

                // Envia email para o vendedor
                $sellerDetails = [
                    'seller_name' => $product->user->name,
                    'seller_email' => $product->user->email,
                    'buyer_name' => Auth::user()->name,
                    'buyer_address' => Auth::user()->address ?? 'Não informado',
                    'items' => [
                        [
                            'name' => $item['name'],
                            'quantity' => $item['quantity'],
                        ]
                    ],
                    'total' => $item['price'] * $item['quantity'],
                ];

                Mail::to($sellerDetails['seller_email'])->send(new PedidoFinalizadoVendedor($sellerDetails));
            }

            // Calcula o total do pedido
            $total = array_reduce($cart, function ($carry, $item) {
                return $carry + ($item['price'] * $item['quantity']);
            }, 0);

            // Prepara os detalhes do pedido para o email do comprador
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

            // Envia o email com os detalhes do pedido para o comprador
            Mail::to($orderDetails['user_email'])->send(new PedidoFinalizado($orderDetails));

            // Limpa o carrinho após finalizar o pedido
            session()->forget('cart');

            // Confirma a transação
            DB::commit();

            return response()->json(['success' => 'Pedido finalizado com sucesso!']);
        } catch (\Exception $e) {
            // Desfaz a transação em caso de erro
            DB::rollBack();
            Log::error('Erro ao finalizar pedido: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao processar o pedido.'], 500);
        }
    }


}
