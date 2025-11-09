<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

class CartController extends Controller
{
    private function cartRows()
    {
        return DB::table('cart_items')
            ->where('cart_items.user_id', Auth::id())
            ->join('products', 'products.id', '=', 'cart_items.product_id')
            ->select('products.id as product_id', 'products.name', 'products.photo', 'products.price', 'cart_items.quantity')
            ->get();
    }

    private function rowsTotal($rows): float
    {
        return (float) $rows->reduce(fn($s, $r) => $s + (float)$r->price * (float)$r->quantity, 0.0);
    }

    public function viewCart()
    {
        $cartItems = $this->cartRows()->map(fn($r) => [
            'id' => (int) $r->product_id,
            'name'=> $r->name,
            'price' => (float) $r->price,
            'photo' => $r->photo,
            'quantity' => (float) $r->quantity,
            'subtotal' => (float) $r->price * (float) $r->quantity,
        ])->toArray();

        return view('cart.view', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $request->merge(['quantity' => str_replace(',', '.', (string) $request->quantity)]);
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $add = (float) $data['quantity'];

        $row = DB::table('cart_items')->where('user_id', Auth::id())->where('product_id', $product->id)->first();
        $current = $row ? (float) $row->quantity : 0.0;

        abort_if($current + $add > (float) $product->stock, 400, "Estoque insuficiente! Apenas {$product->stock} disponível.");

        $payload = [
            'user_id'=> Auth::id(),
            'product_id' => $product->id,
            'quantity'=> round($current + $add, 3),
            'updated_at' => now(),
        ];

        if ($row) {
            DB::table('cart_items')->where('id', $row->id)->update($payload);
        } else {
            DB::table('cart_items')->insert($payload + ['created_at' => now()]);
        }

        return response()->json(['success' => true, 'message' => 'Produto adicionado ao carrinho com sucesso!']);
    }

    public function updateCart(Request $request, $productId)
    {
        $request->merge(['quantity' => str_replace(',', '.', (string) $request->quantity)]);
        $qty = (float) $request->validate(['quantity' => 'required|numeric|min:0.01'])['quantity'];

        $product = Product::findOrFail($productId);
        abort_if($qty > (float) $product->stock, 400, "Estoque insuficiente! Restam {$product->stock}.");

        $updated = DB::table('cart_items')
            ->where('user_id', Auth::id())->where('product_id', $product->id)
            ->update(['quantity' => round($qty, 3), 'updated_at' => now()]);

        if (!$updated) {
            DB::table('cart_items')->insert([
                'user_id'=> Auth::id(),
                'product_id' => $product->id,
                'quantity'=> round($qty, 3),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success'=> true,
            'subtotal'=> round(((float)$product->price) * $qty, 2),
            'total'=> round($this->rowsTotal($this->cartRows()), 2),
        ]);
    }

    public function deleteItem(Request $request)
    {
        $productId = (int) ($request->input('item_id', $request->query('item_id')) ?? 0);
        abort_if(!$productId, 422, 'Parâmetro item_id ausente.');

        $deleted = DB::table('cart_items')
            ->where('user_id', Auth::id())->where('product_id', $productId)
            ->delete();

        abort_if(!$deleted, 404, 'Item não encontrado no carrinho.');

        return response()->json([
            'success' => 'Item removido do carrinho com sucesso!',
            'total'   => $this->rowsTotal($this->cartRows()),
        ]);
    }

    public function getCartSummary()
    {
        return response()->json(['total' => $this->rowsTotal($this->cartRows())]);
    }

    // Checkout Mercado Pago
    public function checkoutMP()
    {
        $rows = $this->cartRows();
        abort_if($rows->isEmpty(), 422, 'Carrinho vazio');

        $items = [];
        $total = 0.0;

        foreach ($rows as $r) {
            $qty   = (float) $r->quantity;
            $price = round((float) $r->price, 2);
            if ($price <= 0) continue;

            $items[] = array_filter([
                'title'       => ($r->name ?? "Produto {$r->product_id}") . ' (' . rtrim(rtrim(number_format($qty, 3, ',', ''), '0'), ',') . ')',
                'quantity'    => 1,
                'unit_price'  => round($price * $qty, 2),
                'currency_id' => 'BRL',
                'picture_url' => $r->photo ? url('storage/'.$r->photo) : null,
            ]);
            $total += $price * $qty;
        }

        $order = DB::transaction(function () use ($rows, $total) {
            $payload = [
                'user_id'     => Auth::id(),
                'total_price' => round($total, 2),
                'status'      => 'Pendente',
            ];

            if (Schema::hasColumn('orders', 'product_id')) {
                $payload['product_id'] = (int) ($rows->first()->product_id ?? 0);
            }
            if (Schema::hasColumn('orders', 'quantity')) {
                $payload['quantity']   = (float) $rows->sum('quantity');
            }

            $order = Order::create($payload);

            foreach ($rows as $r) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => (int) $r->product_id,
                    'quantity'   => (float) $r->quantity,
                    'price'      => (float) $r->price,
                ]);
            }

            return $order;
        });

        $successUrl = route('orders.success', ['order' => $order->id]);

        $token = config('services.mercadopago.token') ?? env('MP_ACCESS_TOKEN');
        MercadoPagoConfig::setAccessToken($token);

        $payload = [
            'items'=> $items,
            'external_reference' => (string) $order->id,
            'payer'=> [
                'name'  => Auth::user()->name  ?? 'Cliente',
                'email' => Auth::user()->email ?? 'comprador+teste@example.com'
            ],
            'back_urls' => [
                'success' => $successUrl
            ],
        ];

        if (Str::startsWith($successUrl, 'https://')) {
            $payload['auto_return'] = 'approved';
        }
        if (Str::startsWith(url('/'), 'https://')) {
            $payload['notification_url'] = route('mp.webhook');
        }

        try {
            $pref = (new PreferenceClient())->create($payload);
            return response()->json(['preference_id' => $pref->id, 'order_id' => $order->id], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (MPApiException $e) {
            $resp   = $e->getApiResponse();
            $status = method_exists($resp, 'getStatusCode') ? $resp->getStatusCode() : 500;
            $body   = method_exists($resp, 'getContent') ? $resp->getContent() : $e->getMessage();
            return response()->json(['mp_error' => $body], $status);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
