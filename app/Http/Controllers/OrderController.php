<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product'])              // << mudou
            ->latest()
            ->get()
            ->map(function ($order) {
                // janela de cancelamento (ex.: 1h)
                $window  = 3600;
                $elapsed = now()->diffInSeconds($order->created_at);

                $order->cancel_time_left  = $order->status === 'Processando'
                    ? max(0, $window - $elapsed) : 0;

                $order->cancel_expires_at = $order->status === 'Processando'
                    ? $order->created_at->copy()->addSeconds($window) : null;

                // total do pedido somando itens (se não tiver coluna total)
                $order->computed_total = $order->items->sum(function ($i) {
                    $price = $i->price ?? optional($i->product)->price ?? 0;
                    return $price * (int)$i->quantity;
                });

                return $order;
            });

        return view('account.orders', compact('orders'));
    }
    public function update(Request $request, Order $order)
{

    if ($order->user_id !== Auth::id()) {
        $msg = 'Você não tem permissão para alterar este pedido.';
        return $request->expectsJson()
            ? response()->json(['success' => false, 'message' => $msg], 403)
            : back()->withErrors($msg);
    }


    $request->validate([
        'status' => 'required|in:Processando,Retirado,Cancelado',
    ]);


    if ($request->input('status') === 'Cancelado') {
        $elapsed = now()->diffInSeconds($order->created_at);
        $cancelTimeLeft = max(0, 3600 - $elapsed);

        if ($order->status !== 'Processando' || $cancelTimeLeft <= 0) {
            $msg = 'Pedido não pode ser cancelado (fora do prazo ou status inválido).';
            return $request->expectsJson()
                ? response()->json(['success' => false, 'message' => $msg], 422)
                : back()->withErrors($msg);
        }
    }


    $order->update(['status' => $request->input('status')]);


    if ($request->expectsJson()) {
        return response()->json(['success' => true]);
    }
    return redirect()->route('orders.index')
        ->with('success', 'Status do pedido atualizado com sucesso!');
}


    public function mySales()
    {
        $vendas = Order::whereHas('items.product', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with(['items.product', 'user'])
            ->latest()
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
    public function success(Request $request, Order $order)
    {
        $order->update(['status' => 'Aprovado']);

       DB::table('cart_items')->where('user_id', $order->user_id)->delete();

        return view('orders.success', compact('order'));
    }

    public function failure(Request $request, Order $order)
    {
        $order->update(['status' => 'Falhou']);
        return view('orders.failure', compact('order'));
    }

    public function pending(Request $request, Order $order)
    {
        $order->update(['status' => 'Pendente']);
        return view('orders.pending', compact('order'));
    }
}

