<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class PagoController extends Controller
{
    public function webhook(Request $request)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $type   = $request->input('type') ?: $request->input('topic') ?: $request->header('X-Topic');
        $id     = data_get($request->input('data'), 'id') ?? $request->input('id');
        $action = $request->input('action');

        if (($type === 'payment' || str_contains((string)$action, 'payment')) && $id) {
            try {
                $payment = (new PaymentClient())->get((int) $id);
                $orderId = (int) data_get($payment, 'external_reference');
                $status  = strtolower((string) data_get($payment, 'status'));

                if ($orderId && ($order = Order::with(['items.product.user','user'])->find($orderId))) {
                    $map = [
                        'approved'   => 'Concluido',
                        'pending'    => 'Pendente',
                        'rejected'   => 'Cancelado',
                        'cancelled'  => 'Cancelado',
                        'refunded'   => 'Cancelado',
                        'in_process' => 'Pendente',
                    ];
                    $novo = $map[$status] ?? $order->status;

                    if ($order->status !== $novo) {
                        $order->update(['status' => $novo]);
                        if ($novo === 'Concluido') {
                            $this->enviarEmailsPagamentoAprovado($order);
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('[MP Webhook] Falhou', ['err' => $e->getMessage()]);
            }
        }

        return response()->json(['ok' => true], 200);
    }

    public function success(Request $request, Order $order)
    {
        $statusParam = strtolower((string) $request->query('status', ''));
        $colStatus   = strtolower((string) $request->query('collection_status', ''));
        $isApproved  = in_array($statusParam, ['approved','aproved'], true)
                    || in_array($colStatus,   ['approved','aproved'], true);

        if ($request->filled('payment_id')) {
            try {
                MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
                $payment   = (new PaymentClient())->get((int) $request->query('payment_id'));
                $mpStatus  = strtolower((string) ($payment->status ?? ''));
                $isApproved = $isApproved || ($mpStatus === 'approved');
            } catch (\Throwable $e) {
                Log::warning('[MP Return] Falha consulta', ['err' => $e->getMessage()]);
            }
        }

        if ($isApproved && $order->status !== 'Concluido') {
            try {
                $order->update(['status' => 'Concluido']);
                $order->loadMissing(['items.product.user','user']);
                $this->enviarEmailsPagamentoAprovado($order);
            } catch (\Throwable $e) {
                Log::warning('[MP Return] Falhou concluir/enviar', ['err' => $e->getMessage()]);
            }
        }

        return view('orders.success', compact('order'));
    }

    private function enviarEmailsPagamentoAprovado(Order $order): void
    {
        $order->loadMissing(['items.product.user','user']);

        try {
            $buyer = $order->user;
            if ($buyer?->email) {
                $orderDetails = [
                    'user_name' => $buyer->name,
                    'total'     => $this->totalPedido($order),
                    'items'     => $order->items->map(fn($i) => [
                        'name'           => $i->product->name,
                        'price'          => (float)$i->price,
                        'quantity'       => (float)$i->quantity,
                        'seller_address' => $i->product->user->address ?? 'Endereço não informado',
                    ])->toArray(),
                ];

                Mail::send('cart.buyer_email', ['orderDetails' => $orderDetails], function ($m) use ($buyer, $order) {
                    $m->to($buyer->email)->subject("Pedido #{$order->id} aprovado");
                });
            }
        } catch (\Throwable $e) {
            Log::warning('[Email] Comprador falhou', ['order_id' => $order->id, 'err' => $e->getMessage()]);
        }

        try {
            $bySeller = $order->items->groupBy(fn($i) => $i->product->user_id);

            foreach ($bySeller as $sellerItems) {
                $seller = $sellerItems->first()->product->user;
                if (!$seller?->email) continue;

                $sellerDetails = [
                    'seller_name' => $seller->name,
                    'buyer_name'  => $order->user?->name,
                    'total'       => $sellerItems->sum(fn($i) => (float)$i->price * (float)$i->quantity),
                    'items'       => $sellerItems->map(fn($i) => [
                        'name'     => $i->product->name,
                        'quantity' => (float)$i->quantity,
                    ])->toArray(),
                ];

                Mail::send('cart.seller_email', ['sellerDetails' => $sellerDetails], function ($m) use ($seller, $order) {
                    $m->to($seller->email)->subject("Novo pedido aprovado — #{$order->id}");
                });
            }
        } catch (\Throwable $e) {
            Log::warning('[Email] Vendedor falhou', ['order_id' => $order->id, 'err' => $e->getMessage()]);
        }
    }

    private function totalPedido(Order $order): float
    {
        if (!is_null($order->total_price)) return (float) $order->total_price;
        $items = $order->relationLoaded('items') ? $order->items : $order->items()->get();
        return (float) $items->sum(fn($i) => (float)$i->price * (float)$i->quantity);
    }
}
