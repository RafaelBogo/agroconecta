<?php

namespace App\Http\Controllers;

use App\Mail\PedidoFinalizado;
use App\Mail\PedidoFinalizadoVendedor;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use Illuminate\Support\Facades\Schema;
use MercadoPago\Exceptions\MPApiException;




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

    if ($request->has('quantity')) {
        $request->merge(['quantity' => str_replace(',', '.', $request->quantity)]);
    }

    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'required|numeric|min:0.01', 
    ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = (float) $request->quantity;

        $cart = session()->get('cart', []);
        $currentCartQuantity = 0;
        foreach ($cart as $item) {
            if (($item['id'] ?? null) == $product->id) {
                $currentCartQuantity = (int) $item['quantity'];
                break;
            }
        }
        if ($currentCartQuantity + (int) $request->quantity > (int) $product->stock) {
            return response()->json([
                'success' => false,
                'error' => "Estoque insuficiente! Apenas {$product->stock} unidade(s) disponível(is)."
            ], 400);
        }

        $found = false;
        foreach ($cart as &$item) {
            if (($item['id'] ?? null) == $product->id) {
                $item['quantity'] += (int) $request->quantity;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $cart[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'photo' => $product->photo,
                'quantity' => (int) $request->quantity,
            ];
        }

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
            $cart = array_values($cart);
            session()->put('cart', $cart);

            $total = array_reduce($cart, fn($carry, $i) => $carry + ($i['price'] * $i['quantity']), 0);

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
        $total = array_reduce($cart, fn($carry, $i) => $carry + ($i['price'] * $i['quantity']), 0);
        return response()->json(['total' => $total]);
    }

    // Fluxo API MP
    public function checkoutMP(Request $request)
    {

        \Log::info('[checkoutMP] POST recebido', ['cart' => session('cart')]);

        $cart = session('cart', []);
        if (!$cart || !count($cart)) {
            return response()->json(['error' => 'Carrinho vazio'], 422);
        }

        $items = [];
        $total = 0.0;
        $sumQty = 0;

        foreach ($cart as $idx => $item) {
            $qtd = max(1, (int) ($item['quantity'] ?? 1));
            $preco = round((float) ($item['price'] ?? 0), 2);
            if ($preco <= 0)
                continue;

            $row = [
                'title' => $item['name'] ?? "Produto " . (($item['id'] ?? $idx)),
                'quantity' => $qtd,
                'unit_price' => $preco,
                'currency_id' => 'BRL',
            ];
            if (!empty($item['photo'])) {
                $row['picture_url'] = url('storage/' . $item['photo']);
            }

            $items[] = $row;
            $total += $preco * $qtd;
            $sumQty += $qtd;
        }

        if (!count($items)) {
            return response()->json(['error' => 'Itens inválidos no carrinho'], 422);
        }

        $orderPayload = [
            'user_id' => Auth::id(),
            'total_price' => round($total, 2),
            'status' => 'Processando',
        ];

        if (Schema::hasColumn('orders', 'product_id')) {
            $orderPayload['product_id'] = $cart[0]['id'] ?? null;
        }
        if (Schema::hasColumn('orders', 'quantity')) {
            $orderPayload['quantity'] = $sumQty > 0 ? $sumQty : 1;
        }

        $order = Order::create($orderPayload);
        $successUrl = route('orders.success', ['order' => $order->id]);
        $failureUrl = route('orders.failure', ['order' => $order->id]);
        $pendingUrl = route('orders.pending', ['order' => $order->id]);
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
        $client = new PreferenceClient();

        $payload = [
            'items' => $items,
            'external_reference' => (string) $order->id,
            'payer' => [
                'name' => Auth::user()->name ?? 'Cliente',
                'email' => Auth::user()->email ?? 'comprador+teste@example.com',
            ],
            'back_urls' => [
                'success' => $successUrl,
                'failure' => $failureUrl,
                'pending' => $pendingUrl,
            ],
        ];

        if (Str::startsWith($successUrl, 'https://')) {
            $payload['auto_return'] = 'approved';
        }
        if (Str::startsWith(url('/'), 'https://')) {
            $payload['notification_url'] = route('mp.webhook');
        }

        try {
            $pref = $client->create($payload);

            return response()->json([
                'preference_id' => $pref->id,
                'order_id' => $order->id,
            ]);
        } catch (MPApiException $e) {
            $resp = $e->getApiResponse();
            $status = method_exists($resp, 'getStatusCode') ? $resp->getStatusCode() : null;
            $content = method_exists($resp, 'getContent') ? $resp->getContent() : null;
            $contentForLog = is_object($content) ? json_decode(json_encode($content), true) : $content;

            \Log::error('[MP Preference Error]', [
                'status' => $status,
                'content' => $contentForLog,
                'payload' => $payload,
                'items' => $items,
            ]);

            return response()->json([
                'mp_error' => $contentForLog ?: $e->getMessage()
            ], $status ?: 500);
        } catch (\Throwable $e) {
            \Log::error('[MP Preference Throwable]', ['msg' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
