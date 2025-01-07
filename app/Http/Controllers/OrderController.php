<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Listar pedidos do usuário logado
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('product')->get();

        return view('account.orders', compact('orders'));
    }

    // Atualizar o status de um pedido
    public function update(Request $request, Order $order)
    {
        // Verifica se o pedido pertence ao usuário logado
        if ($order->user_id !== Auth::id()) {
            return redirect()->back()->withErrors('Você não tem permissão para alterar este pedido.');
        }

        $request->validate([
            'status' => 'required|in:Processando,Retirado,Cancelado',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.index')->with('success', 'Status do pedido atualizado com sucesso!');
    }
}
