<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;

class PagoController extends Controller
{
    public function webhook(Request $request)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $type   = $request->input('type') ?: $request->input('topic') ?: $request->header('X-Topic');
        $id     = data_get($request->input('data'), 'id') ?? $request->input('id');
        $action = $request->input('action');

        Log::info('[MP Webhook] recebi', [
            'type' => $type, 'action' => $action, 'id' => $id, 'raw' => $request->all()
        ]);

        if (($type === 'payment' || str_contains((string)$action, 'payment')) && $id) {
            try {
                $client   = new PaymentClient();
                $payment  = $client->get((int) $id);

                $orderId  = (int) data_get($payment, 'external_reference');
                $statusMP = data_get($payment, 'status');

                if ($orderId && ($order = Order::find($orderId))) {
                    $map = [
                        'approved'=> 'Pago',
                        'pending' => 'Processando',
                        'rejected' => 'Falhou',
                        'cancelled'=> 'Cancelado',
                        'refunded' => 'Estornado',
                        'in_process' => 'Processando',
                    ];
                    $novoStatus = $map[$statusMP] ?? $order->status;

                    if ($order->status !== $novoStatus) {
                        $order->status = $novoStatus;
                        $order->save();
                        Log::info('[MP Webhook] Pedido atualizado', [
                            'order_id' => $order->id, 'status' => $novoStatus
                        ]);
                    }
                } else {
                    Log::warning('[MP Webhook] Pedido não encontrado', [
                        'external_reference' => $orderId, 'mp_status' => $statusMP
                    ]);
                }
            } catch (MPApiException $e) {
                $resp = $e->getApiResponse();
                Log::error('[MP Webhook] MPApiException', [
                    'status' => $resp?->getStatusCode(),
                    'content'=> $resp?->getContent()
                ]);
            } catch (\Throwable $e) {
                Log::error('[MP Webhook] Erro geral', ['err' => $e->getMessage()]);
            }
        }

        return response()->json(['ok' => true], 200);
    }

    // PAGINAS DE RETORNO
    public function success(Request $request, Order $order)
    {
        $mpStatus = null;

        if ($request->filled('payment_id')) {
            try {
                MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
                $client = new PaymentClient();
                $payment = $client->get((int) $request->query('payment_id'));
                $mpStatus = $payment->status ?? null;

                if ($mpStatus === 'approved' && $order->status !== 'Pago') {
                    $order->status = 'Pago';
                    $order->save();
                    Log::info('[MP Return] Pedido marcado como Pago', ['order_id' => $order->id]);
                }
            } catch (MPApiException $e) {
                $r = $e->getApiResponse();
                Log::warning('[MP Return] MPApiException', [
                    'status' => $r?->getStatusCode(),
                    'content'=> $r?->getContent()
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
