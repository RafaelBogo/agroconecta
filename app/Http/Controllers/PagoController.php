<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class PagoController extends Controller
{
    public function webhook(Request $request)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $type = $request->input('type') ?: $request->input('topic') ?: $request->header('X-Topic');
        $id   = data_get($request->input('data'), 'id') ?? $request->input('id');

        if ($type === 'payment' && $id) {
            try {
                $client   = new PaymentClient();
                $payment  = $client->get((int) $id);
                $orderId  = (int) data_get($payment, 'external_reference');
                $statusMP = data_get($payment, 'status');

                if ($orderId && ($order = Order::find($orderId))) {
                    $map = [
                        'approved' => 'Pago',
                        'pending'  => 'Processando',
                        'rejected' => 'Falhou',
                    ];
                    $novoStatus = $map[$statusMP] ?? $order->status;

                    if ($order->status !== $novoStatus) {
                        $order->status = $novoStatus;
                        $order->save();
                        Log::info('[MP Webhook] Pedido atualizado', [
                            'order_id' => $order->id,
                            'status'   => $novoStatus,
                        ]);
                    }
                } else {
                    Log::warning('[MP Webhook] Pedido não encontrado', ['external_reference' => $orderId]);
                }
            } catch (\Throwable $e) {
                Log::error('[MP Webhook] Erro ao processar pagamento', ['error' => $e->getMessage()]);
                return response()->json(['ok' => false], 200);
            }
        }
        return response()->json(['ok' => true], 200);
    }

    // PÁGINAS DE RETORNO
    public function success(Request $request, Order $order)
    {
        $mpStatus = null;
        if ($request->filled('payment_id')) {
            try {
                MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
                $client   = new PaymentClient();
                $payment  = $client->get((int) $request->query('payment_id'));
                $mpStatus = $payment->status ?? null;

                if ($mpStatus === 'approved' && $order->status !== 'Pago') {
                    $order->status = 'Pago';
                    $order->save();
                }
            } catch (\Throwable $e) {
                Log::warning('[MP Return] Falha ao consultar pagamento', ['err' => $e->getMessage()]);
            }
        }

        return view('orders.success', [
            'order'    => $order,
            'mpStatus' => $mpStatus,
        ]);
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
