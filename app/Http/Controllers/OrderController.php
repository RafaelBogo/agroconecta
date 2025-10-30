<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.product.user'])
            ->latest()
            ->get();

        return view('account.orders', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $data = $request->validate([
            'status' => 'required|in:Pendente,Concluido,Cancelado',
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Status atualizado.');
    }
    public function mySales()
    {
        $vendas = Order::whereHas('items.product', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with(['items.product.user', 'user']) // comprador
            ->latest()
            ->paginate(20);

        return view('account.mySales', compact('vendas'));
    }

    public function mySalesAnalysis()
    {
        $vendas = Order::whereHas('items.product', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->with(['items.product.user', 'user'])
            ->latest()
            ->get();

        return view('account.mySalesAnalysis', compact('vendas'));
    }

    public function confirmRetirada(Request $request)
    {
        $request->validate(['order_id' => 'required|exists:orders,id']);

        $venda = Order::where('id', $request->order_id)
            ->whereHas('items.product', fn ($q) => $q->where('user_id', Auth::id()))
            ->first();

        if (!$venda) {
            return redirect()
                ->route('seller.mySales')
                ->withErrors(['error' => 'Venda não encontrada ou não autorizada.']);
        }

        $venda->update(['status' => 'Concluido']);

        return redirect()
            ->route('seller.mySales')
            ->with('success', 'Retirada confirmada com sucesso.');
    }
}
