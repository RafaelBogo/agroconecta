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
    public function mySales()
    {
        $vendas = Order::whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })
        ->with('product', 'user') // Inclui informações do produto e do comprador
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
            }) // Garante que o usuário autenticado é o vendedor do produto
            ->first();

        if (!$venda) {
            return redirect()->route('seller.mySales')->withErrors(['error' => 'Venda não encontrada ou não autorizada.']);
        }

        $venda->status = 'Retirado'; // Atualiza o status da venda
        $venda->save();

        return redirect()->route('seller.mySales')->with('success', 'Retirada confirmada com sucesso!');
    }

}
