<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
       $orders = Order::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->map(function ($order) {
    // só calcula countdown se estiver Processando
            if ($order->status === 'Processando') {
                $window = 3600; // 1 hora de janela de cancelamento
                $elapsed = now()->diffInSeconds($order->created_at);

        // valor inicial (segue útil para render inicial)
                $order->cancel_time_left  = max(0, $window - $elapsed);

        // >>> NOVO: instante exato em que expira
                $order->cancel_expires_at = $order->created_at->copy()->addSeconds($window);
            } else {
                $order->cancel_time_left  = 0;
                $order->cancel_expires_at = null;
            }

         return $order;
        });


        return view('account.orders', compact('orders'));
    }

    public function update(Request $request, Order $order)
{
    // 1) Autorização
    if ($order->user_id !== Auth::id()) {
        $msg = 'Você não tem permissão para alterar este pedido.';
        return $request->expectsJson()
            ? response()->json(['success' => false, 'message' => $msg], 403)
            : back()->withErrors($msg);
    }

    // 2) Validação do status
    $request->validate([
        'status' => 'required|in:Processando,Retirado,Cancelado',
    ]);

    // 3) Regra de cancelamento (apenas se status=Cancelado)
    if ($request->input('status') === 'Cancelado') {
        $elapsed = now()->diffInSeconds($order->created_at);
        $cancelTimeLeft = max(0, 3600 - $elapsed); // 1h (ajuste se quiser)

        if ($order->status !== 'Processando' || $cancelTimeLeft <= 0) {
            $msg = 'Pedido não pode ser cancelado (fora do prazo ou status inválido).';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }
    }

    // 4) Atualiza
    $order->update(['status' => $request->input('status')]);

    // 5) Resposta adequada
    if ($request->expectsJson()) {
        return response()->json(['success' => true]);
    }
    return redirect()->route('orders.index')
        ->with('success', 'Status do pedido atualizado com sucesso!');
}


    public function mySales()
    {
        $vendas = Order::whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with('product', 'user')
        ->get();

        return view('account.mySales', compact('vendas'));
    }

    public function confirmRetirada(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $venda = Order::where('id', $request->order_id)
            ->whereHas('product', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if (!$venda) {
            return redirect()->route('seller.mySales')->withErrors(['error' => 'Venda não encontrada ou não autorizada.']);
        }

        $venda->update(['status' => 'Retirado']);

        return redirect()->route('seller.mySales')->with('success', 'Retirada confirmada com sucesso!');
    }
}
