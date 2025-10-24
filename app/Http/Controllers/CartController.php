<?php

namespace App\Http\Controllers;

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
   public function viewCart(Request $request)
{
    $this->ensureLogged();

    $rows = DB::table('cart_items')
        ->where('cart_items.user_id', Auth::id())
        ->join('products', 'products.id', '=', 'cart_items.product_id')
        ->select(
            'cart_items.product_id',
            'cart_items.quantity',
            'products.name',
            'products.photo',
            'products.price'
        )
        ->get();

    $cartItems = $rows->map(function ($r) {
        return [
            'id' => (int) $r->product_id,
            'name'=> $r->name,
            'price'=> (float) $r->price,
            'photo'=> $r->photo,
            'quantity' => (float) $r->quantity,
            'subtotal' => (float) $r->price * (float) $r->quantity,
        ];
    })->toArray();

    Log::info('Conteúdo do carrinho ao exibir (DB):', $cartItems);

    return view('cart.view', compact('cartItems'));
}



  public function addToCart(Request $request)
{
    $this->ensureLogged();

    if ($request->has('quantity')) {
        $request->merge(['quantity' => str_replace(',', '.', $request->quantity)]);
    }

    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'=> 'required|numeric|min:0.01',
    ]);

    $product  = Product::findOrFail($request->product_id);
    $quantity = (float) $request->quantity;

    // quantidade atual
    $existing = DB::table('cart_items')
        ->where('user_id', Auth::id())
        ->where('product_id', $product->id)
        ->first();

    $currentQty = $existing ? (float) $existing->quantity : 0.0;

    // estoque
    if ($currentQty + $quantity > (float) $product->stock) {
        return response()->json([
            'success' => false,
            'error'=> "Estoque insuficiente! Apenas {$product->stock} disponível.",
        ], 400);
    }

    if ($existing) {
        DB::table('cart_items')
            ->where('id', $existing->id)
            ->update([
                'quantity'   => round($currentQty + $quantity, 3),
                'updated_at' => now(),
            ]);
    } else {
        DB::table('cart_items')->insert([
            'user_id'=> Auth::id(),
            'product_id' => $product->id,
            'quantity'=> round($quantity, 3),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    Log::info('Carrinho atualizado (DB) após adicionar produto.', [
        'user_id' => Auth::id(),
        'productId' => $product->id,
        'quantity' => $quantity,
    ]);

    return response()->json(['success' => true, 'message' => 'Produto adicionado ao carrinho com sucesso!']);
}
public function updateCart(Request $request, $id)
{
    $this->ensureLogged();

    $request->merge(['quantity' => str_replace(',', '.', (string) $request->quantity)]);
    $request->validate([
        'quantity' => 'required|numeric|min:0.01',
    ]);

    $product = Product::findOrFail($id);
    $qty     = (float) $request->quantity;

    if ($qty > (float) $product->stock) {
        return response()->json([
            'success' => false,
            'message' => "Estoque insuficiente! Restam {$product->stock}.",
        ], 400);
    }

    $updated = DB::table('cart_items')
        ->where('user_id', Auth::id())
        ->where('product_id', $product->id)
        ->update(['quantity' => round($qty, 3), 'updated_at' => now()]);

    if (!$updated) {
        // se ainda não existe cria
        DB::table('cart_items')->insert([
            'user_id'=> Auth::id(),
            'product_id'=> $product->id,
            'quantity'=> round($qty, 3),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // calcula o subtotal e o total
    $subtotal = round(((float) $product->price) * $qty, 2);
    $rows = DB::table('cart_items')
        ->where('cart_items.user_id', Auth::id())
        ->join('products', 'products.id', '=', 'cart_items.product_id')
        ->select('products.price', 'cart_items.quantity')
        ->get();

    $total = $rows->reduce(fn($c,$r) => $c + ( (float)$r->price * (float)$r->quantity ), 0.0);

    return response()->json([
        'success'  => true,
        'subtotal' => $subtotal,
        'total'    => round($total, 2),
    ]);
}


    public function deleteItem(Request $request)
{
    $this->ensureLogged();

    $itemId = $request->input('item_id', $request->query('item_id'));
    if (!$itemId || !is_numeric($itemId)) {
        return response()->json(['error' => 'Parâmetro item_id ausente.'], 422);
    }
    $itemId = (int) $itemId;

    $deleted = DB::table('cart_items')
        ->where('cart_items.user_id', Auth::id())
        ->where('cart_items.product_id', $itemId)
        ->delete();

    if (!$deleted) {
        return response()->json(['error' => 'Item não encontrado no carrinho.'], 404);
    }

    $rows = DB::table('cart_items')
        ->where('cart_items.user_id', Auth::id())
        ->join('products','products.id','=','cart_items.product_id')
        ->select('products.price','cart_items.quantity')
        ->get();

    $total = $rows->reduce(fn($c,$r) => $c + ((float)$r->price * (float)$r->quantity), 0.0);

    return response()->json([
        'success' => 'Item removido do carrinho com sucesso!',
        'total' => $total,
    ]);
}


   public function getCartSummary()
{
    $this->ensureLogged();

    $rows = DB::table('cart_items')
    ->where('cart_items.user_id', Auth::id())
    ->join('products','products.id','=','cart_items.product_id')
    ->select('products.price','cart_items.quantity')
    ->get();


    $total = $rows->reduce(fn($c,$r) => $c + ((float)$r->price * (float)$r->quantity), 0.0);

    return response()->json(['total' => $total]);
}

    public function checkoutMP(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Faça login para finalizar a compra.'], 403);
    }

    \Log::info('[checkoutMP] POST recebido (DB)', ['user_id' => Auth::id()]);

    $rows = DB::table('cart_items')
        ->where('cart_items.user_id', Auth::id())
        ->join('products', 'products.id', '=', 'cart_items.product_id')
        ->select(
            'products.id as pid',
            'products.name',
            'products.photo',
            'products.price',
            'cart_items.quantity'
        )
        ->get();

    if ($rows->isEmpty()) {
        return response()->json(['error' => 'Carrinho vazio'], 422);
    }

    $items = [];
    $total = 0.0;
    $sumQty = 0.0;

    foreach ($rows as $idx => $r) {
        $qty = (float) $r->quantity;
        $price = round((float) $r->price, 2);
        if ($price <= 0) continue;

        $title = $r->name ?? "Produto {$r->pid}";
        $title .= ' (' . rtrim(rtrim(number_format($qty, 3, ',', ''), '0'), ',') . ')';


        $row = [
            'title' => $title,
            'quantity' => 1,
            'unit_price' => round($price * $qty, 2),
            'currency_id' => 'BRL',
        ];
        if (!empty($r->photo)) {
            $row['picture_url'] = url('storage/' . $r->photo);
        }

        $items[] = $row;
        $total += $row['unit_price'];
        $sumQty += $qty;
    }


    $orderPayload = [
        'user_id' => Auth::id(),
        'total_price'=> round($total, 2),
        'status' => 'Processando',
    ];

    if (Schema::hasColumn('orders', 'product_id')) {
        $first = $rows->first();
        if (!$first || empty($first->pid)) {
            return response()->json(['error' => 'Não foi possível identificar o produto do pedido.'], 422);
        }
        $orderPayload['product_id'] = (int) $first->pid;
    }

    if (Schema::hasColumn('orders', 'quantity')) {
        $orderPayload['quantity'] = $sumQty > 0 ? $sumQty : 1;
    }

    $order = Order::create($orderPayload);

    $successUrl = route('orders.success', ['order' => $order->id]);
    $failureUrl = route('orders.failure', ['order' => $order->id]);
    $pendingUrl = route('orders.pending', ['order' => $order->id]);

    $accessToken = config('services.mercadopago.token') ?? env('MP_ACCESS_TOKEN');

    MercadoPagoConfig::setAccessToken($accessToken);
    $client = new PreferenceClient();

    $payload = [
        'items' => $items,
        'external_reference'=> (string) $order->id,
        'payer' => [
            'name' => Auth::user()->name  ?? 'Cliente',
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
            'order_id'      => $order->id,
        ], 200, [], JSON_UNESCAPED_UNICODE);
    } catch (MPApiException $e) {
        $resp    = $e->getApiResponse();
        $status  = method_exists($resp, 'getStatusCode') ? $resp->getStatusCode() : null;
        $content = method_exists($resp, 'getContent') ? $resp->getContent() : null;
        $contentForLog = is_object($content) ? json_decode(json_encode($content), true) : $content;

        \Log::error('[MP Preference Error]', [
            'status' => $status,
            'content'=> $contentForLog,
            'payload'=> $payload,
            'items'=> $items,
        ]);

        return response()->json(['mp_error' => $contentForLog ?: $e->getMessage()], $status ?: 500);
    } catch (\Throwable $e) {
        \Log::error('[MP Preference Throwable]', ['msg' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    private function ensureLogged(): void
{
    if (!Auth::check()) {
        abort(403, 'Faça login para usar o carrinho.');
    }
}
}
