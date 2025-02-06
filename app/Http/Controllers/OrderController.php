<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Lista os pedidos do usuário autenticado
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('product')
            ->get()
            ->map(function ($order) {
                // Calcula o tempo restante com base no horário de criação do pedido
                $elapsedTime = now()->diffInSeconds($order->created_at);
                $order->cancel_time_left = max(0, (0.1 * 60) - $elapsedTime); // 10 minutos (600 segundos)
                return $order;
            });

        return view('account.orders', compact('orders'));
    }

    // Atualiza o status do pedido
    public function update(Request $request, Order $order)
    {
        // Verifica se o pedido pertence ao usuário autenticado
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->withErrors('Você não tem permissão para alterar este pedido.');
        }

        $request->validate([
            'status' => 'required|in:Processando,Retirado,Cancelado',
        ]);

        // Atualiza o status do pedido
        $order->update(['status' => $request->status]);

        return redirect()->route('orders.index')->with('success', 'Status do pedido atualizado com sucesso!');
    }

    // Lista as vendas realizadas pelo vendedor autenticado
    public function mySales()
    {
        $vendas = Order::whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with('product', 'user') // Inclui informações sobre o produto e o comprador
        ->get();

        return view('account.mySales', compact('vendas'));
    }

    // Confirma a retirada de um pedido
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

        // Verifica se a venda pertence ao vendedor autenticado
        if (!$venda) {
            return redirect()->route('seller.mySales')->withErrors(['error' => 'Venda não encontrada ou não autorizada.']);
        }

        // Atualiza o status da venda para 'Retirado'
        $venda->update(['status' => 'Retirado']);

        return redirect()->route('seller.mySales')->with('success', 'Retirada confirmada com sucesso!');
    }
}
