<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class PagoController extends Controller
{
    public function webhook(Request $request)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $type = $request->input('type') ?: $request->input('topic') ?: $request->header('X-Topic');
        $id = data_get($request->input('data'), 'id') ?? $request->input('id');
        $action = $request->input('action');

        Log::info('[MP Webhook] recebi', ['type' => $type, 'action' => $action, 'id' => $id, 'raw' => $request->all()]);

        if (($type === 'payment' || str_contains((string) $action, 'payment')) && $id) {
            try {
                $client = new PaymentClient();
                $payment = $client->get((int) $id);

                $orderId = (int) data_get($payment, 'external_reference');
                $statusMP = data_get($payment, 'status');

                if ($orderId && ($order = Order::find($orderId))) {
                    $map = [
                        'approved' => 'Concluido',
                        'pending' => 'Pendente',
                        'cancelled' => 'Cancelado',
                    ];
                    $novoStatus = $map[$statusMP] ?? $order->status;

                    if ($order->status !== $novoStatus) {
                        $order->status = $novoStatus;
                        $order->save();
                        Log::info('[MP Webhook] Pedido atualizado', ['order_id' => $order->id, 'status' => $novoStatus]);
                    }
                }
            }
        }

        return response()->json(['ok' => true], 200);
    }

    public function success(Request $request, Order $order)
    {
        $mpStatus = null;

        if ($request->filled('payment_id')) {
            try {
                MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
                $client = new PaymentClient();
                $payment = $client->get((int) $request->query('payment_id'));
                $mpStatus = $payment->status ?? null;

                if ($mpStatus === 'approved' && $order->status !== 'Concluido') {
                    $order->status = 'Concluido';
                    $order->save();
                    Log::info('[MP Return] Pedido marcado como Concluido', ['order_id' => $order->id]);

                    $order->loadMissing(['items.product.user', 'user']);

                    try {
                        if ($order->user?->email) {
                            Mail::raw(
                                "Seu pedido #{$order->id} foi aprovado. Total: R$ " . number_format($order->total_price, 2, ',', '.'),
                                function ($m) use ($order) {
                                    $m->to($order->user->email)->subject("Pedido #{$order->id} aprovado");
                                }
                            );
                        }

                        $sellerEmails = $order->items
                            ->map(fn($it) => $it->product?->user?->email)
                            ->filter()->unique()->values();

                        foreach ($sellerEmails as $email) {
                            Mail::raw(
                                "Você vendeu! Pedido #{$order->id} foi aprovado.",
                                function ($m) use ($email, $order) {
                                    $m->to($email)->subject("Nova venda aprovada — Pedido #{$order->id}");
                                }
                            );
                        }

                        Log::info('[Email] Enviado confirmação de pagamento', [
                            'order_id' => $order->id,
                            'buyer' => $order->user?->email,
                            'sellers' => $sellerEmails ?? [],
                        ]);
                    } catch (\Throwable $e) {
                        Log::error('[Email] Falha ao enviar e-mails de pagamento', [
                            'order_id' => $order->id,
                            'err' => $e->getMessage(),
                        ]);
                    }
                }
            } catch (MPApiException $e) {
                $r = $e->getApiResponse();
                Log::warning('[MP Return] MPApiException', [
                    'status' => $r?->getStatusCode(),
                    'content' => $r?->getContent()
                ]);
            } catch (\Throwable $e) {
                Log::warning('[MP Return] Falha ao consultar pagamento', ['err' => $e->getMessage()]);
            }
        }

        return view('orders.success', compact('order', 'mpStatus'));
    }

    public function failure(Order $order)
    {
        return view('orders.failure', compact('order'));
    }

    public function pending(Order $order)
    {
        return view('orders.pending', compact('order'));
    }
}
