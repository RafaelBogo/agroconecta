<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
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

        if (($type === 'payment' || str_contains((string) $action, 'payment')) && $id) {
            try {
                $payment = (new PaymentClient())->get((int) $id);

                $orderId = (int) data_get($payment, 'external_reference');
                $status  = strtolower((string) data_get($payment, 'status'));

                if ($orderId && ($order = Order::with(['items.product.user','user'])->find($orderId))) {
                    $map = [
                        'approved' => 'Concluido',
                        'pending' => 'Pendente',
                        'rejected' => 'Cancelado',
                        'cancelled' => 'Cancelado',
                        'refunded' => 'Cancelado',
                        'in_process' => 'Pendente',
                    ];
                    $novo = $map[$status] ?? $order->status;

                    if ($order->status !== $novo) {
                        $order->update(['status' => $novo]);
                        Log::info('[MP Webhook] Pedido atualizado', ['order_id' => $order->id, 'status' => $novo]);

                        if ($novo === 'Concluido') {
                            $order->loadMissing(['items.product.user','user']);
                            $this->enviarEmailsPagamentoAprovado($order);
                            $this->limparCarrinhoDoComprador($order);
                        }
                    }
                } else {
                    Log::warning('[MP Webhook] Pedido não encontrado', [
                        'external_reference' => $orderId, 'mp_status' => $status
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning('[MP Webhook] Falha', ['err' => $e->getMessage()]);
            }
        }

        return response()->json(['ok' => true], 200);
    }
    public function success(Request $request, Order $order)
    {
        $mpStatus = null;

        $statusParam = strtolower((string) $request->query('status', ''));
        $colStatus = strtolower((string) $request->query('collection_status', ''));
        $isApproved = in_array($statusParam, ['approved', 'aproved'], true)
                    || in_array($colStatus,   ['approved', 'aproved'], true);

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
                $this->limparCarrinhoDoComprador($order);
                Log::info('[MP Return] Pedido marcado como Concluido', ['order_id' => $order->id]);
            } catch (\Throwable $e) {
                Log::warning('[MP Return] Falhou concluir/enviar', ['err' => $e->getMessage()]);
            }
        }

        return view('orders.success', compact('order', 'mpStatus'));
    }

    public function failure(Order $order)
    {
        if ($order->status !== 'Concluido') {
            try { $order->update(['status' => 'Cancelado']); } catch (\Throwable $e) {}
        }
        return view('orders.failure', compact('order'));
    }
    public function pending(Request $request, Order $order)
    {
        if ($order->status !== 'Concluido') {
            try { $order->update(['status' => 'Pendente']); } catch (\Throwable $e) {}
        }
        return view('orders.pending', compact('order'));
    }
    private function enviarEmailsPagamentoAprovado(Order $order): void
    {
        $order->loadMissing(['items.product.user','user']);

        $fromAddress = config('mail.from.address') ?: ('no-reply@' . parse_url(config('app.url') ?: request()->getSchemeAndHttpHost(), PHP_URL_HOST));
        $fromName    = config('mail.from.name') ?: 'AgroConecta';

        $buyer = $order->user;
        if ($buyer?->email) {
            $orderDetails = [
                'user_name' => $buyer->name,
                'total' => $this->totalPedido($order),
                'items' => $order->items->map(fn($i) => [
                    'name' => $i->product->name,
                    'price' => (float)$i->price,
                    'quantity' => (float)$i->quantity,
                    'seller_address' => $i->product->user->address ?? 'Endereço não informado',
                ])->toArray(),
            ];

            $html = view('cart.buyer_email', ['orderDetails' => $orderDetails])->render();
            Log::info('[Email->Buyer] Render OK', [
                'order_id' => $order->id,
                'to' => $buyer->email,
                'html_len' => strlen($html),
            ]);

            try {
                Mail::html($html, function ($m) use ($buyer, $order, $fromAddress, $fromName) {
                    $m->from($fromAddress, $fromName)
                      ->to($buyer->email)
                      ->subject("Pedido #{$order->id} aprovado");
                });
                Log::info('[Email->Buyer] Enviado', ['order_id' => $order->id, 'to' => $buyer->email]);
            } catch (\Throwable $e) {
                Log::error('[Email->Buyer] Falhou', ['order_id' => $order->id, 'to' => $buyer->email, 'err' => $e->getMessage()]);
            }
        } else {
            Log::warning('[Email->Buyer] Sem e-mail do comprador', ['order_id' => $order->id]);
        }

        $bySeller = $order->items->groupBy(fn($i) => $i->product->user_id);
        foreach ($bySeller as $sellerItems) {
            $seller = $sellerItems->first()->product->user;
            $to = $seller?->email;

            if (!$to) {
                Log::warning('[Email->Seller] Vendedor sem e-mail', [
                    'order_id' => $order->id,
                    'seller_id' => $seller?->id,
                    'sellerName' => $seller?->name,
                ]);
                continue;
            }

            $sellerDetails = [
                'seller_name' => $seller->name,
                'buyer_name'  => $order->user?->name,
                'total' => $sellerItems->sum(fn($i) => (float)$i->price * (float)$i->quantity),
                'items' => $sellerItems->map(fn($i) => [
                    'name' => $i->product->name,
                    'quantity' => (float)$i->quantity,
                ])->toArray(),
            ];

            $html = view('cart.seller_email', ['sellerDetails' => $sellerDetails])->render();
            Log::info('[Email->Seller] Render OK', [
                'order_id' => $order->id,
                'to' => $to,
                'html_len' => strlen($html),
                'items' => count($sellerDetails['items']),
            ]);

            try {
                Mail::html($html, function ($m) use ($to, $order, $fromAddress, $fromName) {
                    $m->from($fromAddress, $fromName)
                      ->to($to)
                      ->subject("Novo pedido aprovado — #{$order->id}");
                });
                Log::info('[Email->Seller] Enviado', ['order_id' => $order->id, 'to' => $to]);
            } catch (\Throwable $e) {
                Log::error('[Email->Seller] Falhou', ['order_id' => $order->id, 'to' => $to, 'err' => $e->getMessage()]);
            }
        }
    }
    private function limparCarrinhoDoComprador(Order $order): void
    {
        try {
            $deleted = DB::table('cart_items')->where('user_id', $order->user_id)->delete();
            Log::info('[Cart] Carrinho limpo após aprovação', ['order_id' => $order->id, 'user_id' => $order->user_id, 'deleted' => $deleted]);
        } catch (\Throwable $e) {
            Log::warning('[Cart] Falha ao limpar carrinho', ['order_id' => $order->id, 'user_id' => $order->user_id, 'err' => $e->getMessage()]);
        }
    }
    private function totalPedido(Order $order): float
    {
        if (!is_null($order->total_price)) {
            return (float) $order->total_price;
        }
        $items = $order->relationLoaded('items') ? $order->items : $order->items()->get();
        return (float) $items->sum(fn($i) => (float)$i->price * (float)$i->quantity);
    }
}
