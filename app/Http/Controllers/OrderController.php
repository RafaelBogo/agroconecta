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
                $elapsedTime = now()->diffInSeconds($order->created_at);
                $order->cancel_time_left = max(0, (0.1 * 60) - $elapsedTime);
                return $order;
            });

        return view('account.orders', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->withErrors('Você não tem permissão para alterar este pedido.');
        }

        $request->validate([
            'status' => 'required|in:Processando,Retirado,Cancelado',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.index')->with('success', 'Status do pedido atualizado com sucesso!');
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
